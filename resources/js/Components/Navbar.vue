<template>
  <nav class="navBar">
    <ul class="navBar__link">
      <li><router-link :to="{ name: 'home' }">Home</router-link></li>
      <li><router-link :to="{ name: 'about' }">About</router-link></li>
      <li><router-link :to="{ name: 'pdf' }">PDF</router-link></li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "navbar",
};
</script>

<style lang="scss">
h1 {
  font-size: 10em;
  margin-top: 0;
}
.navBar {
  padding: 5px;
  background-color: #fff;
  &__link {
    display: flex;
    margin: 0;
    li {
      margin: 10px;
      list-style-type: none;
      font-size: 17px;
      a {
        text-decoration: none;
        color: #555555;
      }
    }
  }
}

button {
  padding: 10px;
  background-color: #FFF;
  border: 1px solid #555;
  color: #555;
  cursor: pointer;
  outline: none;
  width: 200px;
}
button:hover {
  background-color: #000;
  color: #FFF;
}
</style>
