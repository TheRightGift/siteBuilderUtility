<template>
    <div>
        <NavbarComponent :categories="categories" :loggedIn="loggedIn" @showEditNavMenu="showEditNavMenu($event)" :email="email"/>
        <HeroComponent :loggedIn="loggedIn" @showHeroEditor="showHeroEditor($event)" :hero="hero" :timestamp="timestamp" />
        <CategoryComponent :categories="categories" />
        <FeaturedProductsComponent :products="products" @showProductEditor="showProductEditor"/>
        <OffersComponent />
        <shipping-details />
        <FooterComponent />
    </div>
</template>
<script>
import CategoryComponent from './CategoryComponent.vue';
import FeaturedProductsComponent from './FeaturedProductsComponent.vue';
import HeroComponent from './HeroComponent.vue';
import NavbarComponent from './NavbarComponent.vue';
import OffersComponent from './OffersComponent.vue';
import FooterComponent from './FooterComponent.vue';
import ShippingDetails from './SellingPointComponent.vue';

export default {
    components: { NavbarComponent, HeroComponent, OffersComponent, FeaturedProductsComponent, CategoryComponent, FooterComponent, ShippingDetails },
    props: {
        loggedIn: Boolean,
        // brandname: String,
        categories: Array,
        hero: Object,
        products: Array,
        timestamp: Number,
        email: String,
    },
    
    methods: {
        showEditNavMenu (evt) {
            this.$emit('showEditNavMenu', evt);
            console.log('Home');
        },
        showHeroEditor (evt) {
            this.$emit('showHeroEditor', evt);
            console.log('Home');
        },
        showProductEditor(evt) {
            this.$emit('showProductEditor', evt);
        }
    }
}
</script>
<style>
    :root {
      --primary-color: #fff;
    }
</style>