<template>
    <div class="row noMarginBottom">
        <div class="col l12 featuredPrdtBackground">
            <div class="container">
                <h2 class="centerAlign">Featured Products</h2>
                <div class="row" @click="showProductEditor">
                    <!-- Loop through your products using v-for -->
                    <div
                        class="col l3 s12 product"
                        v-for="(product, index) in seededProducts"
                        :key="index"
                    >
						<router-link
							:to="
								loggedIn
									? `#!`
									: {
											name: `product-details`,
											params: {
												product_name: product.title,
											},
										}
							"
						>
							<img
								class="responsive-img"
								:src="renderImage(product)"
								alt=""
								loading="lazy"
							/>
							<h3>{{ product.title }}</h3>
						</router-link>
                        <span class="category grey-text">{{
                            product.category.name
                        }}</span>
                        <span class="pexamplerice">
                            <span
                                class="oldPrice grey-text"
                                v-if="product.oldPrice"
                                >{{ product.oldPrice }}</span
                            >
                            <span class="curPrice">{{ formatPrice(product.amount) }}</span>
                        </span>
                        <div class="rating">
                            <i
                                class="material-icons"
                                v-for="star in 5"
                                :key="star"
                                >{{
                                    product.rating > star
                                        ? "star"
                                        : "star_border"
                                }}</i
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import priceMixixn from "@/mixin/priceMixin";
    export default {
        computed: {
            displayedProducts() {
                if (this.products && this.products.length > 0) {
                    return this.products;
                } else if (this.seededProducts && this.seededProducts.length > 0) {
                    return this.seededProducts;
                } else {
                    return [];
                }
            },
        },
        mixins: [priceMixixn],
        data() {
            return {
                renderImage(prd) {
                    if (prd !== undefined && prd.images[0] !== undefined) {
                        return prd.images[0].url
                    }
                },
				seededProducts: [
                    {
                        title: "Yellow Shoes",
                        category: {
                            name: "Men",
                        },
                        oldPrice: "$150.00",
                        curPrice: "$120.00",
                        rating: 3,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2021/03/sports-shoe3-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Dark Blue Jeans",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$150.00",
                        rating: 2,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-m-jeans1-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Blue Denim Jeans",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$150.00",
                        rating: 3,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-w-jeans2-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Blue Denim Short",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$130.00",
                        rating: 4,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-w-jeans1-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Anchor Bracelet",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$120.00",
                        rating: 5,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-accessory2-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Boho Bangle Bracelet",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$120.00",
                        rating: 4,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-accessory1-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Yellow Shoes",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$120.00",
                        rating: 2,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-bag1-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                    {
                        title: "Bright Red",
                        category: {
                            name: "Men",
                        },
                        curPrice: "$120.00",
                        rating: 3,
                        images: [
                            {
                                url: "https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2017/12/product-bag3-768x768.jpg",
                            },
                        ],

                        link: "#",
                    },
                ],
            };
        },
        methods: {
            showProductEditor() {
                if (this.loggedIn) {
                    this.$emit("showProductEditor", true);
                }
            },
        },
        mounted() {
            if (this.products.length > 0) {
                this.seededProducts = this.products;
            }
        },
        props: {
            products: Array,
			loggedIn: Boolean,
        },
        watch: {
            products: {
                handler(newProducts) {
                    if (newProducts.length > 0) {
                        this.seededProducts = [...newProducts]; // Make a copy of the new products
                    }
                },
                deep: true, // Enable deep watching to detect changes within the array
            },
        },
    };
</script>

<style scoped>
h2 {
    font-size: 2.53rem;
}
    .noMarginBottom {
        margin-bottom: 0 !important;
    }

    .row .col.featuredPrdtBackground {
        background-color: #f5f7f9;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-top: 3vh;
        padding-bottom: 4vh;
    }

    .container {
        width: 88%;
        max-width: unset;
    }

    .container .row {
        margin-bottom: 5vh;
    }

    .centerAlign {
        text-align: center;
    }

    .product {
        display: flex;
        flex-direction: column;
        margin-bottom: 6vh;
    }

    .product h3 {
        font-size: 1.8rem;
        margin: 0.5rem 0;
        padding: 0;
        color: #000;
    }

    .category {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .oldPrice {
        text-decoration: line-through;
    }

    .price {
        font-weight: 500;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .rating i {
        font-size: 1.3rem;
    }
    img {
        width: 300px;
        height: 300px;
    }
    /* MOBILE */
    @media only screen and (max-width: 767px) {
        img {
            width: 100%;
            height: unset;
        }
    }
</style>
