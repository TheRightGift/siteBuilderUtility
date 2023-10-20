<template>
    <div class="productCatSection" id="productCategorySection"  @click="showCategoryEditEditor">
        <div
            v-if="categories && categories.length > 0"
            class="productCatSectionInner"
        >
            <div
                v-for="(category, index) in categories.slice(0, 3)"
                :key="index"
                class="productCat"
                :style="{
                    backgroundImage: `url(${
                        !category.image ? seeder[index].image : category.image
                    })`,
                }"
            >
                <div class="shadow">
                    <div class="catDetails">
                        <h3>{{ category.name }}</h3>
                        <p>
                            {{
                                !category.image
                                    ? seeder[index].description
                                    : category.description
                            }}
                        </p>
                        <div class="heroCtaContainer">
                            <router-link
                                :to="{
                                    name: `product-search-category`,
                                    params: {
                                        category_name:
                                            category.name ?? `category`,
                                    },
                                    query: {
                                        additionalData:
                                            category.id ?? `category_id`,
                                    },
                                }"
                                >Shop Now</router-link
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="productCatSectionInner" v-else>
            <div
                v-for="(category, index) in seeder.slice(0, 3)"
                :key="index"
                class="productCat"
                :style="{ backgroundImage: `url(${category.image})` }"
            >
                <div class="shadow">
                    <div class="catDetails">
                        <h3>{{ category.name }}</h3>
                        <p>{{ category.description }}</p>
                        <div class="heroCtaContainer">
                            <a href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
  <script>
    export default {
        data() {
            return {
                seeder: [
                    {
                        name: "Women Fashion",
                        description:
                            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                        image: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/women-fashion-free-img.jpg",
                    },
                    {
                        name: "Men Fashion",
                        description:
                            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                        image: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/men-fashion-free-img.jpg",
                    },
                    {
                        name: "Accessories",
                        description:
                            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
                        image: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/footwear-free-img.jpg",
                    },
                ],
                imgDimensionHeight: 880,
                imgDimensionWidth: 800,
            };
        },
        methods: {
            showCategoryEditEditor() {
                if (this.loggedIn) {
                    this.$emit("showEditNavMenu", {
                        evt: true,
                        width: this.imgDimensionWidth,
                        height: this.imgDimensionHeight,
                    });
                }
            },
        },
        props: {
            categories: Array,
            loggedIn: Boolean,
        },
        watch: {
            categories(newVal) {
                if (newVal) {
                    newVal.forEach((category, i) => {
                        if (i < this.seeder.length) {
                            // Update existing seeder items
                            this.seeder[i].name = category.name;
                            this.seeder[i].image =
                                category.image || this.seeder[i].image;
                            this.seeder[i].description =
                                category.description || this.seeder[i].description;
                        } else {
                            // Create new seeder items if there are more categories
                            this.seeder.push({
                                name: category.name,
                                image: category.image || null,
                                description: category.description || null,
                            });
                        }
                    });
                }
            },
        },
    };
</script>
  
<style scoped>
    .productCatSection {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .productCatSectionInner {
        width: 80vw;
        display: flex;
        justify-content: space-between;
    }

    .productCat {
        height: 65vh;
        width: 25vw;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
    }

    .productCat .shadow {
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        height: 100%;
    }

    .productCat .shadow .catDetails {
        padding: 6vh 2.7vw;
    }

    .catDetails h3 {
        margin: 0;
        color: #fff;
        font-family: Lato;
        font-size: 1.6rem;
        font-style: normal;
        font-weight: 700;
        line-height: 39px;
    }

    .catDetails p {
        color: #fff;
        font-family: Lato;
        font-size: 1rem;
        font-style: normal;
        font-weight: 400;
        line-height: 1.85713rem;
        margin: 1.5vh 0 5vh;
    }

    .heroCtaContainer a {
        padding: 1.5vh 2vw;
        text-transform: uppercase;
        border: thin solid #fff;
        background-color: #fff;
        color: #000;
        text-decoration: none;
    }

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        .productCatSection {
            height: unset;
            display: block;
            padding: 3vh 0;
        }
        .productCatSectionInner {
            margin: 0 auto;
            width: 90vw;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .productCat {
            height: 45vh;
            width: 100%;
            margin-bottom: 3vh;
        }

        h1 {
            font-size: 2.7rem;
            line-height: 70%;
            margin: 2.8rem 0 1.68rem 0;
        }
    }
</style>
  