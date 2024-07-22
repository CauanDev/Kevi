<template>
  <div id="app" class="select-none">
    <NavBar/>
    <router-view/> 
  </div>


</template>

<script>
import NavBar from "./components/Navbar/NavBar.vue"
export default {
  name: 'App',
  components: {
    NavBar
  }
}
</script>
<style>
@media (min-width:640px) and (max-width: 1236px) {
  .mobile {
      flex-direction: column;
  }
}
</style>