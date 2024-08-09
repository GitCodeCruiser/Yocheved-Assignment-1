<template>
    <div ref="pdfContent">
      <h1>{{ title }}</h1>
      <p>{{ description }}</p>
      <!-- Add more content here as needed -->
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        title: 'PDF Title',
        description: 'This is the content that will be included in the PDF.'
      };
    },
    methods: {
      getPdfContent() {
        return this.$refs.pdfContent.innerHTML;
      }
    }
  };
  </script>
  