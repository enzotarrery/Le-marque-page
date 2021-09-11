<template>
  <div class="col-6 col-12-narrower">
    <section class="box special">
    <span class="image featured">
      <img :src="image" :alt="produit.nom">
    </span>
      <h3>{{ produit.nom }}</h3>
      <p id="description">{{ produit.description }}</p>
      <p id="prix">{{ produit.prix }}€</p>
      <ul class="actions special">
        <a :href="'http://localhost:8000/produit/' + produit.id" class="button alt">En savoir plus</a>
      </ul>
    </section>
  </div>
</template>

<script>
export default {
  name: "Produit",
  computed: {
    image() {
      return require('@/assets/images/' + this.produit.image.nom)
    }
  },
  props: {
    produit: Object
  }
}
</script>

<style scoped>

</style>