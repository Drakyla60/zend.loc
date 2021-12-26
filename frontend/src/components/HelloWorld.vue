<template>
  <div class="hello">
    <h1>{{ msg }}</h1>

<!--      <button v-on:click="count++">Счётчик кликов — {{ count }}</button>.-->
<!--    <p>Изначальное сообщение: «{{ message }}»</p>-->
<!--    <p>Сообщение задом наперёд: «{{ reversedMessage }}»</p>-->

<!--    {{testFunction()}}-->
<!--    {{mounted}}-->


    <section v-if="errored">
      <p>We're sorry, we're not able to retrieve this information at the moment, please try back later</p>
    </section>

    <section v-else>
      <div v-if="loading">Loading...</div>

      <div
          v-else
          v-for="(data, index) in info" :key="index"
          class="currency"
      >
        {{ data.id }} --
        {{ data.title }} --
        {{ data.author | capitalize }} --

      </div>

    </section>


  </div>
</template>

<script>
import axios from "axios";
const data1 = { a: 1 };
export default {
  name: 'HelloWorld',
  props: {
    msg: String,
  },
  data() {
    return {
      count: 0,
      message: 'Привет',
      data1: data1,
      info: null,
      loading: true,
      errored: false
    }
  },
  computed: {
    // геттер вычисляемого значения
    reversedMessage: function () {
      // `this` указывает на экземпляр vm
      return this.message.split('').reverse().join('')
    },


  },
  filters: {
    capitalize: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    }
  },
  methods: {
    mountedApi: function () {
      console.log('Hello')
      return axios
          .get('http://localhost:3000/posts')
          .then(response => (this.info = response.data))
          .catch(
              error => {
                console.log(error);
                this.errored = true;
              })
          .finally(() => (this.loading = false));
      // return data;
    },
    testFunction: function () {
      return console.log('Hello')
    }
  },
  mounted: function () {
    this.mountedApi();
  },





}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
