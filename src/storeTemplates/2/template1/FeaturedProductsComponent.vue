<template>
    <div class="grey lighten-3" @click="showProductEditor">
        <div class="container">
            <div class="row" @click="showProductEditor">
                <div id="test1" class="col s12">
                    <div class="row">
                        <h3 class="blogHeader">Featured Products</h3>
                        <div
                            class="col s12 l2 m3"
                            v-for="(product, index) in seededProducts"
                            :key="index"
                        >
                            <div class="card">
                                <div class="card-content">
                                    <span class="categoryText">{{
                                        product.category.name
                                    }}</span>

                                    <router-link
                                        :to="
                                            loggedIn
                                                ? `#!`
                                                : {
                                                      name: `product-details`,
                                                      params: {
                                                          product_name:
                                                              product.title,
                                                      },
                                                  }
                                        "
                                        class="blue-text"
                                    >
                                        {{ title(product) }}
                                    </router-link>
                                    <img
                                        :src="renderImage(product)"
                                        :alt="product.title"
                                        loading="lazy"
                                        class="responsive-img"
                                    />
                                    <div class="flex justify-between mt-1">
                                        <p class="amount">
                                            {{ formatPrice(product.amount) }}
                                        </p>
                                        <div class="hide-on-med-and-down">
                                            <router-link
                                                :to="
                                                    loggedIn
                                                        ? `#!`
                                                        : {
                                                              name: `product-details`,
                                                              params: {
                                                                  product_name:
                                                                      product.title,
                                                              },
                                                          }
                                                "
                                                class="cartContener"
                                            >
                                                <i
                                                    class="fa-solid fa-cart-arrow-down"
                                                ></i
                                            ></router-link>
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-between pt-2 links hide"
                                    >
                                        <a href="#!" class="rating flex">
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
    .card {
        box-shadow: none;
    }
    .card:hover {
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
            0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
    }    .pt-2 {
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
    }    .categoryText {
        font-size: 0.74987rem;
        color: #878787 !important;
        display: block;
    }    .cartContener {
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
    import priceMixixn from "@/mixin/priceMixin";
    import { useCartStore } from "../../../store/store";
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
            isAuthenticated() {
                const cartStore = useCartStore();
                return cartStore.isAuthenticated;
            },
        },
        mixins: [priceMixixn],
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
                ],
            };
        },
        methods: {
            wishlist(prd) {
                if (this.isAuthenticated) {
                    this.addOrRemove(prd);
                } else {
                    M.toast({
                        html: `Hello, please &nbsp; <a href="/auth/signin"> Login</a>`,
                        displayLength: 9000,
                        classes: "primary",
                    });
                }
            },
            addOrRemove(prd) {
                const cartStore = useCartStore();
                let data = {
                    user_id: cartStore.user.id,
                    product_id: prd.id,
                };
                cartStore.addToMyWishes(data);
            },
            checkWishlist(product) {
                let wished = useCartStore().wishlists.find(
                    (el) => product.id == el.product_id
                );
                return wished;
            },
            classObject(prd) {
                return { "is-active": this.checkWishlist(prd) };
            },
            showProductEditor() {
                if (this.loggedIn) {
                    this.$emit("showProductEditor", true);
                }
            },
            title(prd) {
                if (prd !== undefined) {
                    if (prd.title > 6) {
                        return prd.title.slice(0, 6) + "...";
                    } else {
                        return prd.title;
                    }
                }
            },
            renderImage(prd) {
                if (prd !== undefined && prd.images[0] !== undefined) {
                    return prd.images[0].url;
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
                        this.seededProducts = [...newProducts]; 
                    }
                },
                deep: true,
            },
        },
    };
</script>