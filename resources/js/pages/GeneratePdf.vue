<template>
  <div>
    <div hidden ref="contentToExport">
      <h1>{{ title }}</h1>
      <p>{{ description }}</p>
      <!-- Add more custom HTML content here -->
      <div>
        <h2>Additional Content</h2>
        <ul>
          <li>Item 1</li>
          <li>Item 2</li>
          <li>Item 3</li>
        </ul>
      </div>
    </div>
    <button @click="exportPDF">Export PDF</button>
  </div>
</template>

<script>
import html2pdf from 'html2pdf.js';

export default {
  data() {
    return {
      title: 'My PDF Title',
      description: 'This is a sample description for the PDF.'
    };
  },
  methods: {
    exportPDF() {
      const element = this.$refs.contentToExport;
      const opt = {
        margin: 1,
        filename: 'my-document.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
      };
      html2pdf().from(element).set(opt).save();
    }
  }
};
</script>
