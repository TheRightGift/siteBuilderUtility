<template>
    <div class="container">
        <a href="#!" @click="showOffersEditor">
            <div
                class="bgImg"
                :style="{ backgroundImage: `url(${imageUrlWithTimestamp})` }"
            >
                <div
                    class="flex items-center justify-center gap-3 overflow-auto"
                >
                    <div class="flex-shrink-0">
                        <h1>
                            {{ heroSeeder.description }}
                        </h1>
                    </div>
                    <div class="flex-shrink-0">
                        <button>
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
                                ><em>Starting at</em>
                                <span class="amount"
                                    ><sup></sup
                                    >{{ heroSeeder.discount_percentage
                                    }}<sup>%</sup></span
                                ></router-link
                            >
                        </button>
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                heroSeeder: {
                    title: "Special Offer",
                    subtitle: "Get 20% Discount, Use Code OFF20",
                    description:
                        "SHOP AND, <strong>SAVE BIG</strong> ON HOTTEST TABLETS",
                    discount: "50%",
                    cover: "https://transvelo.github.io/electro-html/2.0/assets/img/1400X206/img1.jpg",
                },
                imgDimensionWidth: 1400,
                imgDimensionHeight: 206,
            };
        },
        mounted() {
            M.AutoInit();
            if (this.offers.length > 0) {
                this.heroSeeder = this.offers[0];
            }
        },
        methods: {
            showOffersEditor() {
                if (this.loggedIn) {
                    this.$emit("showOffersEditor", {
                        evt: true,
                        width: this.imgDimensionWidth,
                        height: this.imgDimensionHeight,
                    });
                }
            },
        },
        computed: {
            imageUrlWithTimestamp() {
                // Append the timestamp as a query parameter to the image URL
                return `${this.heroSeeder.cover}?t=${this.timestamp}`;
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
    a {
        color: #FFF;
    }
    .gap-3 {
        gap: 3vh;
    }
    .bgImg {
        text-transform: uppercase;
        padding: 3.5rem 1.5rem 1.5rem 1.5rem;
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
