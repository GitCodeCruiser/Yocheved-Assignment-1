<template>
  <div class="d-flex justify-content-center">
    <div class="custom-form custom-form-white shadow-lg" style="width: 600px; min-height: auto;">
      <div class="form-group">
        <label for="start_time">Extract Data</label>
        <input type="file" @change="handleFileSelection" class="form-control mt-2" />
      </div>
      <div class="d-flex justify-content-end mt-3">
        <CustomButton type="button" :clickHandler="handleFileUpload" text="Upload File" :buttonClass="'submit-button custom-button-blue'" :disabled="false" />
      </div>
    </div>
  </div>
</template>

<script>
import mammoth from 'mammoth';
import sessionApiService from '../api-services/session-api-service';
import CustomButton from '../components/CustomButton.vue';

export default {
  components: {
    CustomButton,
  },
  data() {
    return {
      selectedFile: null,
      parsedData: [],
    };
  },
  methods: {
    handleFileSelection(event) {
      this.selectedFile = event.target.files[0];
    },
    async handleFileUpload() {
      if (this.selectedFile) {
        const arrayBuffer = await this.readFile(this.selectedFile);
        const text = await this.extractTextFromDocx(arrayBuffer);
        this.parsedData = this.extractDataFromText(text);

        sessionApiService.addMultipleSessions(this.parsedData).then(({ data }) => {
          if (data.status) {
            this.$toast.success(data.message);
            this.$router.push({ name: 'Sessions' });
          } else {
            this.$toast.error(data.message);
          }
        });
      } else {
        this.$toast.error('Please select a file first.');
      }
    },
    readFile(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (event) => resolve(event.target.result);
        reader.onerror = (error) => reject(error);
        reader.readAsArrayBuffer(file);
      });
    },
    async extractTextFromDocx(arrayBuffer) {
      const result = await mammoth.extractRawText({ arrayBuffer });
      return result.value;
    },
    extractDataFromText(text) {
      const dataEntries = [];
      const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 0);

      let currentEntry = {};
      let inSample2 = false;
      let headersPassed = false;

      const addedEntries = new Set();

      for (let i = 0; i < lines.length; i++) {
        const line = lines[i];

        if (line === 'Subject' || line === 'Practical Knowledge Improvement Plan (PKIP)') {
          inSample2 = true;
          headersPassed = false;
          continue;
        }

        if (inSample2) {
          if (!headersPassed) {
            if (line === 'Target' || line === 'Improvement target') {
              headersPassed = true;
            }
            continue;
          }

          if (line === 'History' || line === 'Economy' || line.startsWith('Bx ')) {
            if (Object.keys(currentEntry).length > 0) {
              this.addUniqueEntry(dataEntries, currentEntry, addedEntries);
            }
            currentEntry = {};
          } else if (line.match(/^\d{4}-\d{2}-\d{2}$/)) {
            if (!currentEntry.fromDate) {
              currentEntry.fromDate = line;
            } else {
              currentEntry.toDate = line;
            }
          } else if (line.match(/^\d+$/)) {
            currentEntry.target = line;
            if (currentEntry.fromDate && currentEntry.toDate) {
              this.addUniqueEntry(dataEntries, currentEntry, addedEntries);
              currentEntry = {};
            }
          }
        }
      }

      // Sample 1 and 3 Patterns
      const sessionPattern = /(?:Session start date|Start Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Session end date|End Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Improvement target|target)\s*(\d+)/g;
      let match;
      while ((match = sessionPattern.exec(text)) !== null) {
        this.addUniqueEntry(dataEntries, {
          fromDate: match[1],
          toDate: match[2],
          target: match[3],
        }, addedEntries);
      }

      const dateRangePattern = /(\d{4}-\d{2}-\d{2})\s*to\s*(\d{4}-\d{2}-\d{2})\s*(\d+)\s*per\s*session/g;
      while ((match = dateRangePattern.exec(text)) !== null) {
        this.addUniqueEntry(dataEntries, {
          fromDate: match[1],
          toDate: match[2],
          target: match[3],
        }, addedEntries);
      }

      return dataEntries;
    },
    // Helper method to add unique entries
    addUniqueEntry(dataEntries, entry, addedEntries) {
      const entryKey = `${entry.fromDate}-${entry.toDate}-${entry.target}`;
      if (!addedEntries.has(entryKey)) {
        dataEntries.push({ ...entry });
        addedEntries.add(entryKey);
      }
    }
  },
};
</script>
