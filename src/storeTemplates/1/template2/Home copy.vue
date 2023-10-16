<template>
    <div>
        <NavbarComponent :brandname="brandname" :categories="categories" :loggedIn="loggedIn" @showEditNavMenu="showEditNavMenu($event)" :email="email"/>
        <!-- <HeroComponent :loggedIn="loggedIn" @showHeroEditor="showHeroEditor($event)" :hero="hero" :timestamp="timestamp" /> -->
        <HeroComponent/>
        <CategoryComponent :categories="categories" />
        <FeaturedProductsComponent :products="products" @showProductEditor="showProductEditor" :loggedIn="loggedIn"/>
        <BlogComponent />
        <!-- ADD TESTIMONIALS HERE -->
        <!-- <OffersComponent :loggedIn="loggedIn" @showHeroEditor="showHeroEditor($event)" :hero="hero" :timestamp="timestamp" :offers="offers"/> -->
        <SellingPointComponent @showSellingPointEditor="showSellingPointEditor($event)" :loggedIn="loggedIn" :sellPoint="sellPoint" />
        <FooterComponent :brandname="brandname" :categories="categories" />
    </div>
</template>

<script>
import CategoryComponent from './CategoryComponent.vue';
import FeaturedProductsComponent from './FeaturedProductsComponent.vue';
import HeroComponent from './HeroComponent.vue';
import NavbarComponent from './NavbarComponent.vue';
import OffersComponent from './OffersComponent.vue';
import FooterComponent from './FooterComponent.vue';
import BlogComponent from './BlogComponent.vue';
import SellingPointComponent from './SellingPointComponent.vue';

export default {
    // , OffersComponent, FeaturedProductsComponent, FooterComponent, SellingPointComponent, BlogComponent
    components: { NavbarComponent, HeroComponent, CategoryComponent, OffersComponent, FeaturedProductsComponent, FooterComponent, SellingPointComponent, BlogComponent },
    data() {
        return {
            brandname: 'DNK',
            categories: [
                {
                    name: "Tops",
                    description:
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                    image: "https://websitedemos.net/fashion-designer-boutique-02/wp-content/uploads/sites/917/2021/07/fashion-designer-template-collection-img-3.jpg",
                },
                {
                    name: "Bottom",
                    description:
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                    image: "https://websitedemos.net/fashion-designer-boutique-02/wp-content/uploads/sites/917/2021/07/fashion-designer-template-collection-img-01.jpg",
                },
                {
                    name: "Accessories",
                    description:
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                    image: "https://websitedemos.net/fashion-designer-boutique-02/wp-content/uploads/sites/917/2021/07/fashion-designer-template-collection-img-02.jpg",
                },
                {
                    name: "Dresses",
                    description:
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                    image: "https://websitedemos.net/fashion-designer-boutique-02/wp-content/uploads/sites/917/2021/07/fashion-designer-template-collection-img-04.jpg",
                },
            ],
            loggedIn: false,
            email: 'goziechukwu@gmail.com',
            themecolor: '#0084d6'
        };
    },
    props: {
        loggedIn: Boolean,
        // themecolor: String,
        brandname: String,
        sellPoint: Array,
        categories: Array,
        hero: Object,
        offers: Array,
        products: Array,
        blog: Array,
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
        showSellingPointEditor (evt) {
            this.$emit('showSellingPointEditor', evt);
            console.log('showSellingPointEditor');
        },
        showProductEditor(evt) {
            this.$emit('showProductEditor', evt);
        },
        
    },
    mounted() {
        // M.AutoInit();
    }
}
</script>
<style>
    :root {
      --primary-color: #0084d6;
    }
</style>