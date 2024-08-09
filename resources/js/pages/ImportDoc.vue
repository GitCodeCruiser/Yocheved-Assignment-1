<template>
    <div>
      <input type="file" @change="handleFileUpload" />
      <div v-if="parsedData">
        <h3>Parsed Data</h3>
        <p><strong>From Date:</strong> {{ parsedData.fromDate }}</p>
        <p><strong>To Date:</strong> {{ parsedData.toDate }}</p>
        <p><strong>Target:</strong> {{ parsedData.target }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import mammoth from 'mammoth';
  
  export default {
    data() {
      return {
        parsedData: null,
      };
    },
    methods: {
      async handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
          const arrayBuffer = await this.readFile(file);
          const text = await this.extractTextFromDocx(arrayBuffer);
          this.parsedData = this.extractData(text);
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
      extractData(text) {
        const fromDateMatch = text.match(/Session start date\s*(\d{4}-\d{2}-\d{2})/);
        const toDateMatch = text.match(/Session end date\s*(\d{4}-\d{2}-\d{2})/);
        const targetMatch = text.match(/Improvement target\s*(\d+)/);
  
        return {
          fromDate: fromDateMatch ? fromDateMatch[1] : 'Not found',
          toDate: toDateMatch ? toDateMatch[1] : 'Not found',
          target: targetMatch ? targetMatch[1] : 'Not found',
        };
      },
    },
  };
  </script>
  