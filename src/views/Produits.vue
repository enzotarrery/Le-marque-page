<template>
  <div class="produits">
    <section class="box">
      <h3>Filtrer :</h3>
      <input type="text" placeholder="Rechercher..." v-model="search">
    </section>
    <div v-if="produits.length > 0" class="row">
      <Produit v-for="(produit, index) in searchedProducts" :key="index" :produit="produit"/>
    </div>
    <section v-else="" class="box special">
      <p>Lien incorrecte.</p>
    </section>
  </div>
</template>

<script>
import Produit from "@/components/Produit";
import Axios from "@/api/axios";
Axios()

export default {
  name: 'Produits',
  data() {
    return {
      search: '',
      produits: []
    }
  },
  mounted() {
    Axios().get('produits')
        .then(response => {
          this.produits = response.data["hydra:member"]
        })
        .catch(e => {
          console.log(e)
          sessionStorage.removeItem('token')
          this.$store.commit('disconnect')
          this.$router.push({name: 'Login'})
        })
  },
  computed: {
    searchedProducts() {
      return this.produits.filter(produit =>
          produit.nom
              .toLowerCase()
              .normalize("NFD")
              .replace(/[\u0300-\u036f]/g, "")
              .includes(this.search
                  .toLowerCase()
                  .normalize("NFD")
                  .replace(/[\u0300-\u036f]/g, ""))
      )
    }
  },
  components: {Produit}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
