<template>
    <div id="herocomponent" @click="showHeroEditor">
        <!-- <div
            class="parallax-container"
            :style="{ zIndex: !loggedIn ? -1 : `inherit` }"
        >
            <div class="parallax" @click="showHeroEditor">
                <img :src="imageUrlWithTimestamp" />
            </div>
            <div class="heroContainer" @click="showHeroEditor">
                <div class="heroBackgroundOverlay"></div>
                <div class="heroContent">
                    <h1 class="heroMainHeading">
                        {{ heroSeeder.description }}
                    </h1>
                    <h3 class="heroMinorHeading">{{ heroSeeder.subtitle }}</h3>
                    <div class="heroCtaContainer">
                        <router-link
                            :to="
                                loggedIn
                                    ? `#!`
                                    : {
                                          name: `product-search-category`,
                                          params: {
                                              category_name:
                                              heroSeeder.type == `welcome` ? `all` : heroSeeder.title ?? `offer`,
                                          },
                                          query: {
                                              additionalOfferData:
                                              heroSeeder.type == `welcome` ? `all` : heroSeeder.id ?? `offer_id`,
                                          },
                                      }
                            "
                            style="cursor: pointer;"
                            >Shop Now</router-link
                        >
                        <router-link
                            :to="
                                loggedIn
                                    ? `#!`
                                    : {
                                          name: `product-search-category`,
                                          params: {
                                              category_name:
                                              heroSeeder.type == `welcome` ? `all` : heroSeeder.title ?? `offer`,
                                          },
                                          query: {
                                              additionalData:
                                                  heroSeeder.type == `welcome` ? `all` : heroSeeder.id ?? `offer_id`,
                                          },
                                      }
                            "
                            >Find More</router-link
                        >
                    </div>
                </div>
            </div>
        </div> -->
        <div
            class="hero"
            :style="{ backgroundImage: `url(${imageUrlWithTimestamp})` }"
        >
            <div class="heroInner">
                <h1 class="heroMainHeading">
                    {{ heroSeeder.title }}
                </h1>
                <p>
                    {{ heroSeeder.description }}
                </p>
                <div class="heroCtaContainer">
                    <router-link
                        class="cta"
                        :to="
                            loggedIn
                                ? `#!`
                                : {
                                      name: `product-search-category`,
                                      params: {
                                          category_name:
                                              heroSeeder.type == `welcome`
                                                  ? `all`
                                                  : heroSeeder.title ?? `offer`,
                                      },
                                      query: {
                                          additionalOfferData:
                                              heroSeeder.type == `welcome`
                                                  ? `all`
                                                  : heroSeeder.id ?? `offer_id`,
                                      },
                                  }
                        "
                        style="cursor: pointer"
                        >Shop Collection
                    </router-link>
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
                return `${this.heroSeeder.image}?t=${this.timestamp}`;
            },
        },
        data() {
            return {
                heroSeeder: {
                    title: "Party Wears",
                    description:
                        "In congue venenatis bibendum viverra sit augue elit sed viverra fames blandit.",
                    image: "https://websitedemos.net/fashion-designer-boutique-02/wp-content/uploads/sites/917/2021/07/fashion-designer-template-hero-img-bg.jpg",
                    subtitle: "25% Off On All Products",
                    type: "welcome",
                },
                imgDimensionHeight: 880,
                imgDimensionWidth: 800, 
            };
        },
        methods: {
            showHeroEditor() {
                if (this.loggedIn) {
                    this.$emit("showHeroEditor", {evt: true, width: this.imgDimensionWidth, height: this.imgDimensionHeight});
                }
            },
        },
        mounted() {
            if (this.hero.length > 0) {
                this.heroSeeder = this.hero[0];
            }
        },
        props: {
            loggedIn: Boolean,
            hero: Array,
            timestamp: Number,
        },
        watch: {
            hero(newVal, oldVal) {
                if (newVal.length > 0) {
                    let parallax = document.querySelectorAll(
                        "#herocomponent .parallax"
                    );
                    let carousel = document.querySelectorAll(
                        "#herocomponent .carousel"
                    );
                    if (parallax.length > 0 || carousel.length === 0) {
                        this.heroSeeder = newVal[0];
                    } else if (carousel.length > 0) {
                        this.heroSeeder = newVal[0];
                    }
                }
            },
        },
    };
</script>

<style scoped>
    .hero {
        width: 100%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        height: 90vh;
        background-color: var(--primary-color);
        background-position: center right;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .heroInner {
        width: 34%;
        margin-left: 13vw;
    }
    .heroInner h1 {
        color: #fff;
        font-family: Fahkwang;
        font-size: 8.5rem;
        font-style: normal;
        font-weight: 600;
        line-height: 8.5rem; /* 100% */
        margin: 0;
    }
    .heroInner p {
        color: #000;
        font-family: Montserrat;
        font-size: 1.375rem;
        font-style: normal;
        font-weight: 600;
        line-height: 1.6rem;
    }
    .heroCtaContainer {
        margin-top: 7vh;
    }
    .heroCtaContainer .cta {
        padding: 1vh 2vw;
        text-transform: uppercase;
        border: thin solid#fff;
        background-color: #fff;
        color: #000;
    }

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        .hero {
            width: 100%;
            margin: unset;
            display: block;
            height: 90vh;
            background-position: 40vw 0;
            background-repeat: no-repeat;
            background-size: cover;
            padding-top: 25vh;
        }
        .heroInner {
            width: 100%;
            margin-left: unset;
            margin: 0 auto;
            padding: 0 8vw;
        }
        .heroInner h1 {
            color: #fff;
            font-family: Fahkwang;
            font-size: 3.5rem;
            font-style: normal;
            font-weight: 500;
            line-height: 1;
            margin: 0;
            background-color: var(--primary-color);
            padding: 1vh 0;
        }

        .heroInner p {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.7;
            background-color: var(--primary-color);
            padding: 1vh;
        }

        .heroCtaContainer .cta {
            padding: 1vh 8vw;
        }

        .heroCtaContainer {
            margin-top: 4vh;
        }

        /* .heroContainer {
                width: 100%;
                height: 60vh;
                padding: 12vh 0 10vh;
            }
            .parallax-container {
                height: 60vh;
            }
            .heroContent {
                width: 90vw;
            }
            .heroMainHeading {
                text-align: center;
                width: 100%;
                margin: 0;
                font-size: 2.4rem;
                font-weight: 600;
            }
            .heroMinorHeading {
                text-align: center;
            }
            .heroCtaContainer a {
                display: block;
                width: 100%;
                text-align: center;
                padding: 1vh 2vw;
                text-transform: uppercase;
                border: thin solid #fff;
            }
            .heroCtaContainer a:first-child {
                margin-bottom: 2vh;
            } */
    }
</style>

