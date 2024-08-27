<script setup lang="ts">
  import type { Category } from "@/types/category";
  import axios from "axios";
  import { Head, usePage, router } from "@inertiajs/vue3";
  import { ref, onMounted, onUnmounted } from 'vue';

  defineProps<{
    categories: {
      data: Category[] | undefined,
    },
    price_ranges?: Record<string, [number, number | null]>,
  }>()
  
  const filters = defineModel<{
    q: string,
    category_ids: number[],
    warehouse_check: boolean,
    price_range: string[],
    sort_option: string;
  }>('filters',{
    default:{
      q: '',
      category_ids: [],
      warehouse_check: false,
      price_range: [],
      sort_option: 'newest',
    }
  })
  
  const emit = defineEmits(['searchSubmit'])

  const submit = () => {
    emit('searchSubmit', filters)
  }

  const suggestions = ref([]);
  const showSuggestions = ref(false);
  const selectedIndex = ref(-1);

  const inputText = async() => {
    if(filters.value.q.length > 0){
      const response = await axios.get('/product/suggest', {
        params: { q: filters.value.q }
      })

      suggestions.value = response.data;
      showSuggestions.value = true;
      selectedIndex.value = -1;
    } else {
      suggestions.value = [];
      showSuggestions.value = false;
    }
  }

  const selectSuggestion = (suggestion) => {
    filters.value.q = suggestion.name;
    showSuggestions.value = false;
  };

  const handleKeyDown = (event) => {
    if (!showSuggestions.value) return;

    switch (event.key) {
      case 'ArrowDown':
        event.preventDefault();
        selectedIndex.value = (selectedIndex.value + 1) % suggestions.value.length;
        break;
      case 'ArrowUp':
        event.preventDefault();
        selectedIndex.value = (selectedIndex.value - 1 + suggestions.value.length) % suggestions.value.length;
        break;
      case 'Enter':
        event.preventDefault();
        if (selectedIndex.value !== -1) {
          selectSuggestion(suggestions.value[selectedIndex.value]);
        }
        break;
      case 'Escape':
        showSuggestions.value = false;
        break;
    }
  };

  const handleClickOutside = (event) => {
    if (!event.target.closest('.suggestion-container')) {
      showSuggestions.value = false;
    }
  };

  onMounted(() => {
    document.addEventListener('click', handleClickOutside);
  });

  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
  });
</script>

<template>
  <form @submit.prevent="submit">
    <div class="suggestion-container">
      <input 
        type="text" 
        v-model="filters.q" 
        @input="inputText" 
        role="combobox" 
        @keydown="handleKeyDown" 
        aria-autocomplete="list"
        :aria-expanded="showSuggestions"
        :aria-activedescendant="selectedIndex !== -1 ? `suggestion-${selectedIndex}` : ''"
        />
      <ul
        v-if="showSuggestions && suggestions.length"
        class="suggestions-list" role="listbox"
      >
        <li
          v-for="(suggestion, index) in suggestions"
          :key="suggestion.id"
          @click="selectSuggestion(suggestion)"
          @mouseover="selectedIndex = index"
          :class="{ 'selected': index === selectedIndex }"
          role="option"
          :id="`suggestion-${index}`"
          :aria-selected="index === selectedIndex"
        >
          {{ suggestion.name }}
        </li>
      </ul>
    </div>

    <div>
      <template v-for="category of categories?.data" :key="category.id">
        <label class="label cursor-pointer">
          <span class="label-text">{{ category.name }}</span>
          <input type="checkbox" v-model="filters.category_ids" :value="category.id" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>
    
    <div>
      <template v-for="(range, key) of price_ranges" :key="key">
        <label class="label cursor-pointer">
          <span class="label-text">{{ range[0] }}～{{ range[1] }}円</span>
          <input type="checkbox" v-model="filters.price_range" :value="key" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>

    <div>
      <label class="label cursor-pointer">
        <span class="label-text">店舗在庫を含める</span>
        <input type="checkbox" v-model="filters.warehouse_check" id="warehouse_check" class="checkbox checkbox-sm" />
      </label>
    </div>
    <button type="submit" class="btn btn-sm">送信</button>
  </form>
</template>

<style scoped>
.suggestion-container {
  position: relative;
}

.suggestions-list {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #ccc;
  background-color: white;
  list-style-type: none;
  padding: 0;
  margin: 0;
  z-index: 10;
}

.suggestions-list li {
  padding: 8px;
  cursor: pointer;
}

.suggestions-list li:hover,
.suggestions-list li.selected {
  background-color: #f0f0f0;
}
</style>