<template>
  <div>
    <NavbarComponent
      :brandname="brandname"
      :categories="categories"
      :loggedIn="loggedIn"
      @showEditNavMenu="showEditNavMenu($event)"
      :email="email"
      :branddesc="branddesc"
    />
    <HeroComponent
      :loggedIn="loggedIn"
      @showHeroEditor="showHeroEditor($event)"
      :hero="hero"
      :timestamp="timestamp"
    />
    <CategoryComponent
      :categories="categories"
      :loggedIn="loggedIn"
      @showEditNavMenu="showEditNavMenu($event)"
      :catetimestamp="catetimestamp"
    />
    <FeaturedProductsComponent
      :products="products"
      @showProductEditor="showProductEditor"
      :loggedIn="loggedIn"
    />
    <BlogComponent
      @showEditBlogMenu="showEditBlogMenu"
      :loggedIn="loggedIn"
      :blogs="blogs"
    />
    <ReviewsComponent
      :reviews="reviews"
    />
    <OffersComponent
      :loggedIn="loggedIn"
      @showOffersEditor="showOffersEditor($event)"
      :hero="hero"
      :timestamp="timestamp"
      :offers="offers"
    />
    <SellingPointComponent
      @showSellingPointEditor="showSellingPointEditor($event)"
      :loggedIn="loggedIn"
      :sellPoint="sellPoint"
    />
    <FooterComponent
      :brandname="brandname"
      :categories="categories"
      @showSocialEditor="showSocialEditor"
      :socials="socials"
      :loggedIn="loggedIn"
      :branddesc="branddesc"
    />
  </div>
</template>

<script>
import CategoryComponent from "./CategoryComponent.vue";
import FeaturedProductsComponent from "./FeaturedProductsComponent.vue";
import HeroComponent from "./HeroComponent.vue";
import NavbarComponent from "./NavbarComponent.vue";
import OffersComponent from "./OffersComponent.vue";
import FooterComponent from "./FooterComponent.vue";
import BlogComponent from "./BlogComponent.vue";
import SellingPointComponent from "./SellingPointComponent.vue";
import ReviewsComponent from "./ReviewsComponent.vue";

export default {
  components: {
    NavbarComponent,
    HeroComponent,
    OffersComponent,
    FeaturedProductsComponent,
    CategoryComponent,
    FooterComponent,
    SellingPointComponent,
    BlogComponent,
    ReviewsComponent
},
  props: {
    loggedIn: Boolean,
    brandname: String,
    sellPoint: Array,
    categories: Array,
    hero: Object,
    offers: Array,
    products: Array,
    reviews: Array,
    blogs: Array,
    timestamp: Number,
    catetimestamp: Number,
    email: String,
    socials: Object,
    branddesc: String,
  },
  methods: {
    showEditNavMenu(evt) {
      this.$emit(`showEditNavMenu`, evt);
    },
    showHeroEditor(evt) {
      this.$emit(`showHeroEditor`, evt);
    },
    showOffersEditor(evt) {
      this.$emit(`showOffersEditor`, evt);
    },
    showSellingPointEditor(evt) {
      this.$emit(`showSellingPointEditor`, evt);
    },
    showProductEditor(evt) {
      this.$emit(`showProductEditor`, evt);
    },
    showEditBlogMenu(evt) {
      this.$emit(`showEditBlogMenu`, evt);
    },
    showSocialEditor(evt) {
      this.$emit(`showSocialEditor`, evt);
    },
  },
  mounted() {
    M.AutoInit();
  },
};
</script>
<style>
/* #TODO */
:root {
  /* --primary-color: #fff; */
  --primary-color: #d10e01;
}
/* fixes design issues with dark mode */
body {
  background-color: #fff;
}
</style>
