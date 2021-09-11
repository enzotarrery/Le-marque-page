<template>
  <v-app>
    <v-main>
      <div id="page-wrapper">
        <!-- Header -->
        <header id="header" class="alt">
          <nav id="nav">
            <ul>
              <li><a href="http://localhost:8000/">Accueil</a></li>
              <li><a @click="goTo(1)">La carte</a></li>
              <li><a @click="goTo(2)">Les promotions</a></li>
              <li><a @click="goTo(3)">Les évènements</a></li>
              <li>
                <a v-if="connected" href="#" class="button">{{ username }}</a>
                <a v-else @click="goTo(0)" class="button">Se connecter</a>
              </li>
            </ul>
          </nav>
        </header>

        <!-- Banner -->
        <section id="banner">
          <h2>Le marque page</h2>
          <p>Bienvenue à la maison :)</p>
        </section>

        <!-- Main -->
        <section id="main" class="container">
          <router-view/>
        </section>
      </div>
    </v-main>
  </v-app>
</template>

<script>

export default {
  name: 'App',

  data: () => ({
    drawer: false,
    group: null
  }),
  watch: {
    group () {
      this.drawer = false
    },
  },
  computed: {
    connected() {
      return this.$store.state.connected
    },
    username() {
      return this.$store.state.username
    }
  },
  methods: {
    signIn() {
      this.$router.push({name: 'Login'})
    },
    signOut() {
      this.$store.commit('disconnect')
      window.open('http://localhost:8000/', '_self')
    },
    goTo(id) {
      if (id === 0)
        this.$router.push({name: 'Login'})
      if (id === 1)
        this.$router.push({name: 'Produits'})
      if (id === 2)
        this.$router.push({name: 'Promotions'})
      if (id === 3)
        this.$router.push({name: 'Evenements'})
    }
  }
};
</script>
