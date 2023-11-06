<template>
  <div class="grey lighten-3" @click="showProductEditor">
    <div class="container">
      <div class="row" @click="showProductEditor">
        <div id="test1" class="col s12">
          <div class="row">
            <h3 class="blogHeader">Featured Products</h3>
            <div
              class="col s12 l2 m3"
              v-for="(product, index) in displayedProducts"
              :key="index"
            >
              <div class="card">
                <div class="card-content">
                  <span class="categoryText">{{ product.category.name }}</span>

                  <router-link :to=" loggedIn ? `#!` : { name: `product-details`, params: { product_name: product.title, } } " class="blue-text">
                    {{ title(product) }}
                  </router-link>
                  <img :src="renderImage(product)" :alt="product.title" loading="lazy" class="responsive-img" />
                  <div class="flex justify-between mt-1">
                    <p class="amount"> {{ formatPrice(product.amount, fx, location) }} </p>
                    <div class="hide-on-med-and-down">
                      <router-link :to=" loggedIn ? `#!` : { name: `product-details`, params: { product_name: product.title } } " class="cartContener">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                      </router-link>
                    </div>
                  </div>
                  <div class="flex justify-between pt-2 links hide">
                    <a href="#!" class="rating flex">
                      <i class="material-icons" v-for="star in 5" :key="star">{{
                        product.rating > star ? "star" : "star_border"
                      }}</i>
                    </a>
                    <a
                      href="#!"
                      class="flex items-center gap-1"
                      @click.prevent="wishlist(product)"
                    >
                      <i
                        class="fa-regular fa-heart"
                        :class="classObject(product)"
                        title="Add to Wishlist"
                      ></i>

                      <span></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.is-active {
  color: red;
}
h3 {
  font-size: 1.37462rem;
  margin-bottom: 2vh;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e7eaf3 !important;
  position: relative;
}
.blogHeader::after {
  width: 84px;

  content: " ";
  height: 2px;
  width: 114px;
  display: block;
  background-color: var(--primary-color);
  position: absolute;
  bottom: -1px;
  left: 0;
}
.rating i {
  font-size: 1rem;
}
.tabs {
  background-color: #eeeeee;
}

.tabs .tab a {
  color: #343f49;
}
.tabs .tab a:hover,
.tabs .tab a.active {
  background-color: transparent;
  color: #343f49;
  font-weight: 700;
}
.tabs .tab a:focus,
.tabs .tab a:focus.active {
  background-color: transparent;
}
.card {
  box-shadow: none;
}
.card:hover {
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
.pt-2 {
  padding-top: 2vh;
}
.gap-1 {
  gap: 1vh;
}
.links a {
  color: #848484 !important;
  font-size: 0.81288rem;
}
.blue-text {
  font-size: 0.875rem;
  font-weight: 700;
}
.categoryText {
  font-size: 0.74987rem;
  color: #878787 !important;
  display: block;
}
.cartContener {
  width: 4vh;
  height: 4vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--primary-color);
  border-radius: 100%;
  color: #fff;
}
.amount {
  color: #343f49;
  font-size: 1.25038rem;
  font-weight: 400;
}
@media only screen and (min-width: 1024px) {
  .container {
    width: 85%;
  }
}
</style>
<script>
import templateMixin from "@/mixin/templateMixin";
import { useCartStore } from "../../../store/store";
export default {
  mixins: [templateMixin],
  data() {
    return {
      seededProducts: [
        {
          title: "Bulbs",
          category: {
            name: "Men",
          },
          oldPrice: "$150.00",
          amount: "$120.00",
          rating: 3,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img1.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "Apple Pad",
          category: {
            name: "Men",
          },
          amount: "$150.00",
          rating: 2,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/190X150/img4.png",
            },
          ],

          link: "#",
        },
        {
          title: "Purple 2 Solo Wireless",
          category: {
            name: "Speakers",
          },
          amount: "$150.00",
          rating: 3,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img3.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "Iphone 14 pro",
          category: {
            name: "Speakers",
          },
          amount: "$130.00",
          rating: 4,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img4.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "Sony Camera",
          category: {
            name: "Camera",
          },
          amount: "$120.00",
          rating: 5,
          images: [
            {
              url: "	https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img5.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "Hp Laserjet",
          category: {
            name: "Printers",
          },
          amount: "$120.00",
          rating: 4,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img6.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "PSP 5",
          category: {
            name: "Games",
          },
          amount: "$120.00",
          rating: 2,
          images: [
            {
              url: "	https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img7.jpg",
            },
          ],

          link: "#",
        },
        {
          title: "Sony IPS Camera",
          category: {
            name: "Camera",
          },
          amount: "$120.00",
          rating: 3,
          images: [
            {
              url: "https://transvelo.github.io/electro-html/2.0/assets/img/212X200/img8.jpg",
            },
          ],

          link: "#",
        },
      ],
    };
  },
  methods: {
    classObject(prd) {
      return { "is-active": this.checkWishlist(prd) };
    },
  },
  mounted() {
    if (this.products.length > 0) {
      this.seededProducts = this.products;
    }
    const cartStore = useCartStore();
    cartStore.getIPFromAmazon();
  },
};
</script>
