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
      id="heroSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingHero}" :title="loggedIn ? `Click to Edit Hero Section` : ``"
    />
    <CategoryComponent
      id="productCategorySection"
      :categories="categories"
      :loggedIn="loggedIn"
      @showEditNavMenu="showEditNavMenu($event)"
      :catetimestamp="catetimestamp"
      :class="{styleEditHover: loggedIn, isEditing: isEditingCategory}" :title="loggedIn ? `Click to Edit Category Section` : ``"
    />
    <FeaturedProductsComponent
      :products="products"
      @showProductEditor="showProductEditor"
      :loggedIn="loggedIn"
      id="featuredProductSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingProduct}" :title="loggedIn ? `Click to add/edit product section` : ``"
    />
    <BlogComponent
      @showEditBlogMenu="showEditBlogMenu"
      :loggedIn="loggedIn"
      :blogs="blogs"
    />
    <ReviewsComponent v-if="reviews.length != 0"
      :reviews="reviews"
    />
    <OffersComponent
      :loggedIn="loggedIn"
      @showOffersEditor="showOffersEditor($event)"
      :hero="hero"
      :timestamp="timestamp"
      :offers="offers"
      id="offersSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingOffers}" :title="loggedIn ? `Click to offers section` : ``"
    />
    <SellingPointComponent
      @showSellingPointEditor="showSellingPointEditor($event)"
      :loggedIn="loggedIn"
      :sellPoint="sellPoint"
      id="sellingPointSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingSellingPoint}" :title="loggedIn ? `Click to edit sell point section` : ``"
    />
    <FooterComponent
      :brandname="brandname"
      :categories="categories"
      @showSocialEditor="showSocialEditor"
      :socials="socials"
      :loggedIn="loggedIn"
      id="socialHandleSection"
      :branddesc="branddesc"
      :class="{styleEditHover: loggedIn, isEditing: isEditingFooter}" :title="loggedIn ? `Click to edit social handles point section` : ``"
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

    isEditingHero: Boolean,
    isEditingCategory: Boolean,
    isEditingProduct: Boolean,
    isEditingFooter: Boolean,
    isEditingOffers: Boolean,
    isEditingSellingPoint: Boolean,
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
:root {
<<<<<<< HEAD
  --primary-color: #fff;
  --secondary-color: #000;
=======
  /* --primary-color: #fff; */
  --primary-color: #d91a1a;
>>>>>>> 4fd60eacb1e0a8f1d70f89c5ded1143335d3867c
}
/* fixes design issues with dark mode */
body {
  background-color: #fff;
}
</style>
