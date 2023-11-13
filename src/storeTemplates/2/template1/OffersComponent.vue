<template>
  <div class="container" >
    <a href="#!" @click="showOffersEditor">
      <div
        class="bgImg"
        :style="{ backgroundImage: `url(${imageUrlWithTimestamp})` }"
      >
        <div class="flex items-center justify-center gap-3 overflow-auto">
          <div class="flex-shrink-0">
            <h1 v-html="offerSeeder.description"></h1>
          </div>
          <div class="flex-shrink-0">
            <button>
              <router-link :to=" loggedIn ? `#!` : { name: `product-search-category`, params: {  category_name: offerSeeder.title ?? `offer` }, query: { additionalOfferData: offerSeeder.id ?? `offer_id` } }">
                <em>Starting at</em>
                <span class="amount">{{ offerSeeder.discount_percentage }}<sup>%</sup></span>
              </router-link>
            </button>
          </div>
        </div>
      </div>
    </a>
  </div>
</template>
<script>
import templateMixin from "@/mixin/templateMixin";

export default {
  data() {
    return {
      offerSeeder: {
        title: "Special Offer",
        subtitle: "Get 20% Discount, Use Code OFF20",
        description: "SHOP AND, <strong>SAVE BIG</strong> ON HOTTEST TABLETS",
        discount_percentage: 50,
        cover:
          "https://transvelo.github.io/electro-html/2.0/assets/img/1400X206/img1.jpg",
      },
      imgDimensionWidth: 1400,
      imgDimensionHeight: 206,
    };
  },
  mixins: [templateMixin],
  mounted() {
    if (this.offers.length > 0) {
      this.offerSeeder = this.offers[0];
    }
  },
  computed: {
    imageUrlWithTimestamp() {
      // Append the timestamp as a query parameter to the image URL
      return `${this.offerSeeder.cover}?t=${this.timestamp}`;
    },
  },
};
</script>
<style scoped>
a {
  color: #fff;
}
.gap-3 {
  gap: 3vh;
}
.bgImg {
  text-transform: uppercase;
  padding: 1.5rem 1.5rem;
  color: #333e48;
}
.container {
  width: 85%;
}
h1 {
  font-size: 2.00025rem;
  font-weight: 300;
  margin: 0;
}
button {
  padding: 0.5rem 2.5rem;
  text-transform: uppercase;
  border-radius: 0.4375rem !important;
  background-color: var(--primary-color);
  border: none;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
button em {
  display: block;
  font-size: 0.875rem;
  font-weight: 300;
}
strong {
  font-weight: 700;
}
.amount {
  font-size: 1.87512rem;
  font-weight: 700;
}
sup {
  font-size: 60%;
}
@media only screen and (min-width: 768px) and (max-width: 1023px) {
  button {
    padding: 0.5rem 3rem;
  }
}
@media only screen and (max-width: 767px) {
  .overflow-auto {
    overflow: auto;
  }
  .flex-shrink-0 {
    flex-shrink: 0;
  }
  .justify-center {
    justify-content: flex-start;
  }
}
</style>
