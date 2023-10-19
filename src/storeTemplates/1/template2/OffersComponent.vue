<template>
    <div class="row noMarginBottom">
        <div class="col l12 offerBackground">
            <div class="container">
                <div class="parallax-container" @click="showOffersEditor">
                    <div class="parallax">
                        <img :src="imageUrlWithTimestamp" />
                    </div>

                    <div class="row parallaxContent">
                        <div class="col l6">
                            <h2>{{ heroSeeder.title }}</h2>
                            <p>
                                {{ heroSeeder.description }}
                            </p>
                            <h5>{{ heroSeeder.subtitle }}</h5>
                            <div class="offerCtaContainer">
                                <router-link
                                    :to="
                                        loggedIn
                                            ? `#!`
                                            : {
                                                  name: `product-search-category`,
                                                  params: {
                                                      category_name:
                                                          heroSeeder.title ??
                                                          `offer`,
                                                  },
                                                  query: {
                                                      additionalOfferData:
                                                          heroSeeder.id ??
                                                          `offer_id`,
                                                  },
                                              }
                                    "
                                    >Shop Now</router-link
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
  <script>
    export default {
        computed: {
            imageUrlWithTimestamp() {
                // Append the timestamp as a query parameter to the image URL
                return `${this.heroSeeder.cover}?t=${this.timestamp}`;
            },
        },
        data() {
            return {
                heroSeeder: {
                    title: "Special Offer",
                    subtitle: "Get 20% Discount, Use Code OFF20",
                    description:
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.",
                    discount: "50%",
                    cover: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2019/12/banner-03.jpg",
                },
                imgDimensionHeight: 880,
                imgDimensionWidth: 800,
            };
        },
        mounted() {
            // Initialize Materialize components
            M.AutoInit();
            if (this.offers.length > 0) {
                this.heroSeeder = this.offers[0];
            }
        },
        methods: {
            showOffersEditor() {
                if (this.loggedIn) {
                    this.$emit("showOffersEditor", {evt: true, width: this.imgDimensionWidth, height: this.imgDimensionHeight});
                }
            },
        },
        props: {
            loggedIn: Boolean,
            hero: Array,
            offers: Array,
            timestamp: Number,
        },
        watch: {
            offers(newVal) {
                if (Object.entries(newVal).length > 0) {
                    this.heroSeeder = newVal[0];
                }
            },
        },
    };
</script>
  
<style scoped>
    html,
    body {
        padding: 0;
        margin: 0;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    .row .col.offerBackground {
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 10vh;
        padding-bottom: 7vh;
    }
    .container {
        width: 88%;
        max-width: unset;
    }
    .offerCtaContainer a {
        padding: 1vh 2vw;
        text-transform: uppercase;
        border: thin solid#fff;
        background-color: #fff;
        color: #000;
    }
    .offerCtaContainer a:hover {
        color: #fff;
        background-color: unset;
    }
    /* Parallax */
    .parallax-container {
        height: 70vh;
        display: flex;
        align-items: center;
    }
    .row.parallaxContent {
        width: 100%;
        color: #fff;
        padding-left: 5vw;
    }
    .parallaxContent p {
        font-size: 1.1rem;
    }
    .parallaxContent h5 {
        margin-bottom: 5vh;
    }
    .row.sellingPoints {
        padding: 13vh 0 2vh;
    }

    .row.sellingPoints .col.l3 {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .row.sellingPoints img {
        width: 4vw;
        height: 9vh;
    }
    .row.sellingPoints p {
        font-size: 1.2rem;
    }
</style>
  