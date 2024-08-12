<template>
    <div class="d-flex justify-content-center">
        <!-- Form container for file upload -->
        <div class="custom-form custom-form-white shadow-lg" style="width: 600px; min-height: auto;">
            <div class="form-group">
                <label for="start_time">Extract Data</label>
                <input
                    type="file"
                    @change="handleFileSelection"
                    class="form-control mt-2"
                />
            </div>
            <!-- Button to upload and process the selected file -->
            <div class="d-flex justify-content-end mt-3">
                <CustomButton
                    type="button"
                    :clickHandler="debouncedHandleFileUpload"
                    text="Upload File"
                    :buttonClass="'submit-button custom-button-blue'"
                    :disabled="!selectedFile"
                />
            </div>
        </div>
    </div>
  </template>
  
  <script>
  import mammoth from "mammoth";
  import sessionApiService from "../api-services/session-api-service";
  import CustomButton from "../components/CustomButton.vue";
  import { debounce } from "lodash";
  
  export default {
    components: {
        CustomButton,
    },
    data() {
        return {
            selectedFile: null, // Holds the selected file for upload
            parsedData: [], // Holds the parsed data from the file
        };
    },
    methods: {
        // Handles file selection event
        handleFileSelection(event) {
            this.selectedFile = event.target.files[0];
        },
  
        // Debounced function to handle file upload
        debouncedHandleFileUpload: debounce(function () {
            this.handleFileUpload();
        }, 300),
  
        // Handles file upload and data extraction
        async handleFileUpload() {
            try {
                if (!this.selectedFile) {
                    this.$toast.error("Please select a file first.");
                    return;
                }
  
                // Read the file and extract text
                const arrayBuffer = await this.readFile(this.selectedFile);
                const text = await this.extractTextFromDocx(arrayBuffer);
                this.parsedData = this.extractDataFromText(text);
  
                // Send the extracted data to the server
                const { data } = await sessionApiService.addMultipleSessions(this.parsedData);
                if (data?.status) {
                    this.$toast.success(data.message);
                    this.$router.push({ name: "Sessions" });
                } else {
                    this.$toast.error(data.message || "Failed to upload data.");
                }
            } catch (error) {
                this.$toast.error("An error occurred while uploading the file.");
            }
        },
  
        // Reads the file and returns its ArrayBuffer
        readFile(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (event) => resolve(event.target.result);
                reader.onerror = (error) => reject(error);
                reader.readAsArrayBuffer(file);
            });
        },
  
        // Extracts text from a .docx file using Mammoth
        async extractTextFromDocx(arrayBuffer) {
            const result = await mammoth.extractRawText({ arrayBuffer });
            return result.value;
        },
  
        // Extracts data from the text using various patterns
        extractDataFromText(text) {
            const sessionPattern = /(?:Session start date|Start Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Session end date|End Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Improvement target|target)\s*(\d+)/g;
            const dateRangePattern = /(\d{4}-\d{2}-\d{2})\s*to\s*(\d{4}-\d{2}-\d{2})\s*(\d+)\s*per\s*session/g;
            const addedEntries = new Set();
            const dataEntries = [];
  
            // Parse Sample 2 specific patterns
            this.parseSample2(text, dataEntries, addedEntries);
  
            // General patterns for Sample 1 and 3
            this.matchPattern(text, sessionPattern, dataEntries, addedEntries);
            this.matchPattern(text, dateRangePattern, dataEntries, addedEntries);
  
            return dataEntries;
        },
  
        // Parses Sample 2 data specifically
        parseSample2(text, dataEntries, addedEntries) {
            const lines = text
                .split("\n")
                .map((line) => line.trim())
                .filter((line) => line.length > 0);
  
            let currentEntry = {};
            let inSample2 = false;
            let headersPassed = false;
  
            for (const line of lines) {
                if (line === "Subject" || line === "Practical Knowledge Improvement Plan (PKIP)") {
                    inSample2 = true;
                    headersPassed = false;
                    continue;
                }
  
                if (inSample2) {
                    if (!headersPassed) {
                        if (line === "Target" || line === "Improvement target") {
                            headersPassed = true;
                        }
                        continue;
                    }
  
                    if (line === "History" || line === "Economy" || line.startsWith("Bx ")) {
                        this.addUniqueEntry(dataEntries, currentEntry, addedEntries);
                        currentEntry = {};
                    } else if (line.match(/^\d{4}-\d{2}-\d{2}$/)) {
                        currentEntry.fromDate = currentEntry.fromDate || line;
                        currentEntry.toDate = currentEntry.fromDate ? line : currentEntry.toDate;
                    } else if (line.match(/^\d+$/)) {
                        currentEntry.target = line;
                        if (currentEntry.fromDate && currentEntry.toDate) {
                            this.addUniqueEntry(dataEntries, currentEntry, addedEntries);
                            currentEntry = {};
                        }
                    }
                }
            }
        },
  
        // Matches and processes patterns in the text
        matchPattern(text, pattern, dataEntries, addedEntries) {
            let match;
            while ((match = pattern.exec(text)) !== null) {
                this.addUniqueEntry(
                    dataEntries,
                    {
                        fromDate: match[1],
                        toDate: match[2],
                        target: match[3],
                    },
                    addedEntries
                );
            }
        },
  
        // Adds unique entries to avoid duplicates
        addUniqueEntry(dataEntries, entry, addedEntries) {
            if (!entry.fromDate || !entry.toDate || !entry.target) return;
  
            const entryKey = `${entry.fromDate}-${entry.toDate}-${entry.target}`;
            if (!addedEntries.has(entryKey)) {
                dataEntries.push({ ...entry });
                addedEntries.add(entryKey);
            }
        },
    },
  };
  </script>
  