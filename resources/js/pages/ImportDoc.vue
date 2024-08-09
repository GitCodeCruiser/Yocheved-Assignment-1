<template>
    <div>
      <input type="file" @change="handleFileUpload" />
      <div v-if="parsedData && parsedData.length">
        <h3>Parsed Data</h3>
        <div v-for="(data, index) in parsedData" :key="index">
          <p><strong>Entry {{ index + 1 }}</strong></p>
          <p><strong>From Date:</strong> {{ data.fromDate }}</p>
          <p><strong>To Date:</strong> {{ data.toDate }}</p>
          <p><strong>Target:</strong> {{ data.target }}</p>
          <hr />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import mammoth from 'mammoth';
  
  export default {
    data() {
      return {
        parsedData: [],
      };
    },
    methods: {
      async handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
          const arrayBuffer = await this.readFile(file);
          const text = await this.extractTextFromDocx(arrayBuffer);
          this.parsedData = this.extractDataFromText(text);
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
  
        // Pattern for "Start From" and "End To" entries (Sample 2 - TKIP format)
        const tkipPattern = /Start From\s*(\d{4}-\d{2}-\d{2})\s*End To\s*(\d{4}-\d{2}-\d{2})\s*Target\s*(\d+)/g;
        let match;
        while ((match = tkipPattern.exec(text)) !== null) {
          dataEntries.push({
            fromDate: match[1],
            toDate: match[2],
            target: match[3],
          });
        }
  
        // Pattern for "Session start date" and "Start Date" entries (Sample 1 format)
        const sessionPattern = /(?:Session start date|Start Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Session end date|End Date)\s*(\d{4}-\d{2}-\d{2})\s*(?:Improvement target|target)\s*(\d+)/g;
        while ((match = sessionPattern.exec(text)) !== null) {
          dataEntries.push({
            fromDate: match[1],
            toDate: match[2],
            target: match[3],
          });
        }
  
        // Pattern for "Date to Date" format in Sample 1
        const dateRangePattern = /(\d{4}-\d{2}-\d{2})\s*to\s*(\d{4}-\d{2}-\d{2})\s*(\d+)\s*per\s*session/g;
        while ((match = dateRangePattern.exec(text)) !== null) {
          dataEntries.push({
            fromDate: match[1],
            toDate: match[2],
            target: match[3],
          });
        }
  
        return dataEntries;
      },
    },
  };
  </script>
