<template>
    <div id="indicators-carousel" class="relative" >
      <div class="relative h-56 overflow-hidden md:h-96 ">
        <div
          v-for="(character, index) in characters"
          :key="index"
          class="duration-700 ease-in-out w-full h-full"
          :class="{'hidden': currentSlide !== index, 'block': currentSlide === index}">
            <div >
                <img :src="character.img" class="absolute block w-[50%] -translate-x-1/2 -translate-y-1/2 top-1/3 left-1/2" :alt="character.name">
            </div>
        </div>
      </div>
      <div class="absolute z-30  flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-[70px] left-1/2">
        <button
          v-for="index in characters.length"
          :key="index"
          type="button"
          class="w-3 h-3 rounded-full"
          :class="{ 'bg-blue-500': currentSlide === index - 1, 'bg-gray-300': currentSlide !== index - 1 }"
          @click="goToSlide(index - 1)"
          aria-current="true"
          :aria-label="'Slide ' + index"
        ></button>
      </div>
  

      <button @click="prevSlide" type="button" class="absolute top-0 sm:start-[8%] lg:start-[18%]  z-30 flex items-center justify-center h-full px-2 cursor-pointer"> 
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none"> <!-- Adjusted w-8 h-8 for size -->
          <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
          </svg>
          <span class="sr-only">Previous</span>
        </span>
      </button>
      <button @click="nextSlide" type="button" class="md:end-[18%] end-[1%] absolute top-0 z-30 flex items-center justify-center h-full px-2 cursor-pointer">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none"> <!-- Adjusted w-8 h-8 for size -->
          <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <span class="sr-only">Next</span>
        </span>
      </button>
      
    </div>  
</template>
  
<script>

  import characters from "@/services/images"
  export default {
    name: "CarouselView",
    props:{

    },
    data() {
      return {
        currentSlide: 0,
        characters
      };
    },
    watch: {
    currentSlide(newVal) {
      this.$emit('updateSlide', newVal);
    }
    },
    methods: {
      nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.characters.length;
      },
      prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.characters.length) % this.characters.length;
      },
      goToSlide(index) {
        this.currentSlide = index;
      },
    },
  };
  </script>
  