<template>
  <div class="max-w-7xl mx-auto px-4 py-6">
    <img
      src="https://compasia.my/cdn/shop/files/CA_new_logo_150x@2x.png?v=1740626224"
      alt="CompAsia Malaysia"
      class="mb-4"
      style="max-width: 150px"
    />

    <h2 class="text-2xl font-bold mb-6">📱 Product Master List</h2>

    <!-- Upload and Search Row -->
    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
      <!-- File input -->
      <input
        type="file"
        @change="handleFile"
        class="block w-full md:w-1/2
               text-sm text-gray-700
               bg-white hover:bg-gray-100 transition
               border border-gray-300 rounded-lg
               file:bg-gray-100 file:text-gray-800 file:border-0
               file:px-4 file:py-2 file:font-medium
               file:rounded-l-lg file:rounded-r-none
               file:cursor-pointer"
      />

      <!-- Upload button -->
      <button
        :disabled="!selectedFile || uploadInProgress"
        class="px-6 py-2 rounded transition text-white font-medium"
        :class="uploadInProgress
                ? 'bg-gray-400 cursor-not-allowed'
                : 'bg-emerald-500 hover:bg-emerald-600'"
        @click="uploadFile"
      >
        📤 {{ uploadInProgress ? 'Uploading...' : 'Upload File' }}
      </button>

      <!-- Search input -->
      <input
        type="number"
        v-model="search"
        @input="getProducts"
        placeholder="Search by Product ID"
        class="block w-full md:w-1/3 border border-gray-300 rounded px-4 py-2 ml-auto"
      />
    </div>

    <!-- Upload error message -->
    <div v-if="uploadError" class="text-red-600 text-sm mb-4">
      {{ uploadError }}
    </div>

    <!-- Product table -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200 rounded-md shadow">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="px-4 py-2 border">No.</th>
            <th class="px-4 py-2 border">Product ID</th>
            <th class="px-4 py-2 border">Types</th>
            <th class="px-4 py-2 border">Brand</th>
            <th class="px-4 py-2 border">Model</th>
            <th class="px-4 py-2 border">Capacity</th>
            <th class="px-4 py-2 border">Quantity</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(product, index) in products.data"
            :key="product.id"
            class="hover:bg-gray-50"
          >
            <td class="px-4 py-2 border">
              {{
                products.current_page
                  ? (products.current_page - 1) * products.per_page + index + 1
                  : index + 1
              }}
            </td>
            <td class="px-4 py-2 border">{{ product.product_id }}</td>
            <td class="px-4 py-2 border">{{ product.types }}</td>
            <td class="px-4 py-2 border">{{ product.brand }}</td>
            <td class="px-4 py-2 border">{{ product.model }}</td>
            <td class="px-4 py-2 border">{{ product.capacity }}</td>
            <td class="px-4 py-2 border">{{ product.quantity }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between mt-6">
      <button
        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded disabled:opacity-50"
        :disabled="!products.prev_page_url"
        @click="getPage(products.prev_page_url)"
      >
        ← Previous
      </button>

      <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded disabled:opacity-50"
        :disabled="!products.next_page_url"
        @click="getPage(products.next_page_url)"
      >
        Next →
      </button>
    </div>

    <!-- Page info -->
    <div class="text-sm text-gray-600 text-center mt-2" v-if="products.last_page">
      Page {{ products.current_page }} of {{ products.last_page }}
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useToast } from "vue-toastification";

export default {
  data() {
    return {
      products: { data: [] },
      search: "",
      selectedFile: null,
      uploadInProgress: false,
      uploadError: "",
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      axios.get(`/api/products?product_id=${this.search}`).then((response) => {
        this.products = response.data;
      });
    },
    getPage(url) {
      if (url) {
        axios.get(url).then((response) => {
          this.products = response.data;
        });
      }
    },
    handleFile(event) {
      this.uploadError = "";
      this.selectedFile = event.target.files[0];
    },
    uploadFile() {
      if (!this.selectedFile) return;

      this.uploadInProgress = true;
      this.uploadError = "";

      const toast = useToast();
      const formData = new FormData();
      formData.append("file", this.selectedFile);

      axios
        .post("/api/upload", formData)
        .then(() => {
          toast.success("✅ Upload successful!");
          this.selectedFile = null;
          this.getProducts();
        })
        .catch((error) => {
          const message =
            error.response?.data?.message || "❌ Upload failed. Please try again.";
          this.uploadError = message;
          toast.error(message);
        })
        .finally(() => {
          this.uploadInProgress = false;
        });
    },
  },
};
</script>
