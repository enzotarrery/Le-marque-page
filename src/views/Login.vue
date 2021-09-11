<template>
  <div>
    <section class="box">
      <div>
        <h1 class="h3 mb-3 font-weight-normal">Connectez-vous</h1>
        <label for="inputUsername">Nom d'utilisateur</label>
        <input type="text" v-model="username" name="username" id="inputUsername" class="form-control" required autofocus>
        <label for="inputPassword">Mot de passe</label>
        <input type="password" v-model="password" name="password" id="inputPassword" class="form-control" required>
        <v-btn text color="black" style="margin-top: 0.5rem" @click="submit">
          Connexion
        </v-btn>
      </div>
    </section>
  </div>
</template>

<script>
import Axios from "@/api/axios";
Axios()

export default {
  name: "Login",

  data: () => ({
    username: '',
    password: '',
    result: ''
  }),

  methods: {
    submit () {
      Axios().post('login_check', { username: this.username, password: this.password })
          .then(response => {
            this.token = response.data;
            this.result = 'Connexion réussie'
            this.$store.commit('connect', { username: this.username, token: this.token.token })
            this.$router.push({name: 'Produits'})
          })
          .catch(e => {
            if (e.response) {
              console.log(e.response)
              this.result = 'Nom d\'utilisateur et/ou mot de passe incorrect.'
              this.password = ''
            }
          })
    }
  },
}
</script>

<style scoped>

</style>