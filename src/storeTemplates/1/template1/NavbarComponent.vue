<template>
    <div>
        <nav>
            <div class="nav-wrapper">
                <a
                    :href="loggedIn ? `#!` : `/`"
                    class="brand-logo hide-on-large-only"
                >
                    <span class="brandname">{{ brandname }}</span>
                </a>

                <span class="hide-on-large-only mobileNavRight">
                    <router-link
                        :to="{ name: `Cart` }"
                        class="iconLinks shoppingCartIconContainer mg-rt-2"
                    >
                        <i
                            class="material-icons themeTextColor mobileShoppingIcon"
                            >shopping_basket</i
                        >
                        <span class="notify">{{ cartCount }}</span>
                    </router-link>
                    <a
                        href="#"
                        data-target="mobile-demo"
                        class="sidenav-trigger"
                        ><i class="material-icons">menu</i></a
                    >
                </span>

                <div class="centerFlex hide-on-med-and-down">
                    <a href="/" class="brandLogo"
                        ><span class="brandname">{{ brandname }}</span></a
                    >

                    <ul class="navLinks">
                        <li>
                            <a :href="loggedIn ? `#!` : `/`">Home</a>
                        </li>
                        <span v-show="categories.length <= 3">
                            <li
                                v-for="category in categories"
                                :key="category.id"
                                @click="showCategoryEditEditor"
                            >
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
                                    v-if="!loggedIn"
                                    >{{ category.name }}</router-link
                                >
                                <a href="#productCategorySection" v-else>{{
                                    category.name
                                }}</a>
                            </li>
                        </span>
                        <li v-show="categories.length > 3" class="catDropdown">
                            <a
                                class="dropdown-trigger"
                                href="#productCategorySection"
                                data-target="dropdownCategory"
                                >Categories<i class="material-icons right"
                                    >arrow_drop_down</i
                                ></a
                            >
                        </li>
                        <ul id="dropdownCategory" class="dropdown-content">
                            <li
                                v-for="category in categories"
                                :key="category.id"
                                @click="showCategoryEditEditor"
                            >
                                <router-link
                                    :to="
                                        loggedIn
                                            ? `#!`
                                            : {
                                                  name: `product-search-category`,
                                                  params: {
                                                      category_name:
                                                          category.name ??
                                                          `category`,
                                                  },
                                                  query: {
                                                      additionalData:
                                                          category.id ??
                                                          `category_id`,
                                                  },
                                              }
                                    "
                                    >{{ category.name }}</router-link
                                >
                            </li>
                        </ul>
                        <li>
                            <a href="#blog">Blog</a>
                        </li>
                        <li>
                            <a :href="mailUs">Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="centerFlex hide-on-med-and-down">
                    <ul class="centerFlex">
                        <li>
                            <a
                                href="/auth/signin"
                                class="authLink"
                                v-if="!isAuthenticated"
                                >LOGIN/REGISTER</a
                            >
                            <a
                                v-else
                                :href="
                                    role == `Admin`
                                        ? `/vendor/dashboard`
                                        : `/your_account/dashboard`
                                "
                                >{{ names }}</a
                            >
                        </li>
                        <li>
                            <a
                                class="iconLinks modal-trigger"
                                href="#searchModal"
                            >
                                <i class="material-icons">search</i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="iconLinks withNotifier">
                                <i class="material-icons">favorite_border</i>
                                <span class="notify">{{ wishlistCount }}</span>
                            </a>
                        </li>
                        <li>
                            <router-link
                                :to="{ name: `Cart` }"
                                class="iconLinks withNotifier"
                            >
                                <i class="material-icons">shopping_cart</i>
                                <span class="notify">{{ cartCount }}</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-demo">
            <li class="mobileSidNavBrand">
                <a :href="loggedIn ? `#!` : `/`"
                    ><span class="brandname">{{ brandname }}</span></a
                >
            </li>
            <li
                v-for="category in categories.slice(0, 3)"
                :key="category.id"
                @click="showCategoryEditEditor"
            >
                <router-link
                    :to="
                        loggedIn
                            ? `#!`
                            : {
                                  name: `product-search-category`,
                                  params: {
                                      category_name:
                                          category.name ?? `category`,
                                  },
                                  query: {
                                      additionalData:
                                          category.id ?? `category_id`,
                                  },
                              }
                    "
                    class="link"
                    >{{ category.name }}</router-link
                >
            </li>
            <li>
                <a href="#"> Blog </a>
            </li>
            <li>
                <a :href="mailUs">Contact Us</a>
            </li>

            <li class="divider" tabindex="-1"></li>

            <li>
                <a href="/auth/signin" class="authLink" v-if="!isAuthenticated">
                    <i class="material-icons">person</i>
                    LOGIN/REGISTER
                </a>
                <router-link
                    v-else
                    :to="
                        role == `Admin`
                            ? `/vendor/dashboard`
                            : `/your_account/dashboard`
                    "
                    >{{ names }}</router-link
                >
                <!-- TODO: add dropdown for user specific operations -->
            </li>

            <li>
                <a href="#" class="authLink mobileWishist">
                    <span>
                        <i class="material-icons">favorite_border</i>
                        Wishlist
                    </span>

                    <span class="notify">{{ wishlistCount }}</span>
                </a>
            </li>
            <li>
                <a class="modal-trigger" href="#searchModal">
                    <i class="material-icons left">search</i>
                    Search
                </a>
            </li>
        </ul>
        <!-- Search Modal Structure -->
        <div id="searchModal" class="modal">
            <div class="modal-content">
                <a
                    class="modal-close waves-effect waves-teal btn-flat right closeModal"
                >
                    <i class="material-icons">clear</i>
                </a>
                <div class="row">
                    <div class="col s12 searchFields">
                        <select class="browser-default select">
                            <option value="1">All Categories</option>
                        </select>

                        <div class="input-field">
                            <input
                                placeholder="Product name"
                                type="search"
                                class="browser-default"
                                v-model="searchproduct.productName"
                            />
                        </div>

                        <a class="waves-effect waves-light btn searchBtn">
                            <i class="material-icons">search</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    import apiMixin from "@/mixin/apiMixin";
    import { useCartStore } from "../../../store/store";
    export default {
        mixins: [apiMixin],
        data() {
            return {
                searchproduct: {
                    categoryId: 0,
                    productName: "",
                },
                editFlag: null,
                imgDimensionHeight: 880,
                imgDimensionWidth: 800,
            };
        },
        mounted() {
            localStorage.setItem("previousPage", this.$route.fullPath);
            var elems = document.querySelectorAll(".sidenav");
            var instances = M.Sidenav.init(elems, {
                edge: "left",
            });

            if (this.categories.length > 3) {
                var elems = document.querySelectorAll(".dropdown-trigger");
                var instances = M.Dropdown.init(elems, {
                    constrainWidth: true,
                    coverTrigger: false,
                });
            }
        },
        computed: {
            isAuthenticated() {
                const cartStore = useCartStore();
                return cartStore.isAuthenticated;
            },
            names() {
                const cartStore = useCartStore();
                const names = cartStore.user.names;
                const nameParts = names.split(" ");
                return nameParts[0];
            },
            role() {
                const cartStore = useCartStore();
                const role = cartStore.user.role;
                return role;
            },
            cartCount() {
                const cartStore = useCartStore();
                return cartStore.cartCount;
            },
            wishlistCount() {
                return useCartStore().wishlistItemCount;
            },
            mailUs() {
                return (
                    `mailto:` +
                    this.email +
                    `?subject=Contact%20Us&body=Hello%20Team`
                );
            },
        },
        methods: {
            searchProducts() {
                // TODO: Add your search logic here
            },
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
            brandname: String,
            categories: Array,
            loggedIn: Boolean,
            email: String,
        },
        watch: {},
    };
</script>
  
<style scoped>
    nav {
        background-color: #ffffff;
        height: 10vh;
    }
    nav .nav-wrapper {
        /* width: 100%;
                        height: 10vh; */
        display: flex;
        /* align-items: center; */
        justify-content: space-between;
        padding: 1vh 1vw;
    }
    .centerFlex {
        display: flex;
        align-items: center;
        height: 100%;
    }
    a.brandLogo {
        display: flex;
        align-items: center;
        color: #24262b;
    }
    .brandLogo .brandname {
        margin-left: 1vw;
        font-size: 1.5rem;
        font-weight: 600;
    }
    ul.navLinks {
        display: flex;
        margin: 0;
        margin-left: 5vw;
        height: 100%;
    }
    ul.navLinks li a,
    ul li .link {
        padding: 0 0.7vw;
        height: inherit;
    line-height: inherit;
    }
    ul li a,
    ul li .link {
        text-decoration: none;
        text-transform: uppercase;
        color: #24262b;
        font-weight: 400;
        font-size: 1.1rem;
    }
    ul li .link {
        cursor: pointer;
        display: block;
    }
    ul li a.iconLinks {
        padding: 0 1vw;
    }
    ul li a.authLink {
        margin-right: 2vw;
    }
    ul li a.authLink:hover {
        transition: unset;
        background-color: unset !important;
    }
    ul li a:hover,
    ul li .link:hover {
        color: var(--primary-color);
        background-color: unset !important;
    }
    .catDropdown i.material-icons {
        margin-left: 0;
    }
    .dropdown-content li {
        height: 6vh;
        min-height: unset;
        line-height: 6vh;
    }
    nav i.material-icons {
        color: #24262b;
    }
    .catDropdown i.material-icons {
        color: inherit;
    }
    .iconLinks.withNotifier {
        display: flex;
        cursor: pointer;
    }
    .iconLinks .notify {
        background-color: var(--primary-color);
        color: #ffffff;
        font-size: 0.8rem;
        border-radius: 30%;
        position: relative;
        right: 0.7vw;
        bottom: -4vh;
        height: 2vh;
        line-height: 2vh;
        position: relative;
        right: 0.7vw;
        font-weight: 700;
        width: 2vh;
        display: flex;
        justify-content: center;
    }

    .searchbar {
        background-color: rgba(0, 0, 0, 0.6);
        position: absolute;
        width: 100%;
        top: 0;
        height: 10vh;
    }
    .searchbar .input-field {
        background-color: #ffffff;
        width: 50%;
        margin: 0 auto;
        height: 10vh;
    }
    .searchbar .input-field input {
        padding: 0 0 0 5%;
        width: 95%;
        margin-bottom: 0;
        height: inherit;
    }
    .searchbar .input-field label,
    .searchbar .input-field i {
        height: 10vh;
        line-height: 10vh;
    }
    .input-field input[type="search"] + .label-icon {
        left: 0.5rem;
    }

    /* TODO: TABLET */

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        nav {
            background-color: #ffffff;
            height: 7vh;
        }
        nav .nav-wrapper {
            align-items: center;
            padding: 1vh 2vw;
        }
        nav .nav {
            display: flex;
            justify-content: space-between;
            padding: 0 3vw;
            height: 7vh;
            line-height: 7vh;
        }
        nav .brand-logo {
            left: unset;
            -webkit-transform: unset;
            transform: unset;
            color: var(--primary-color);
            position: unset;
        }
        nav a {
            color: var(--primary-color);
        }
        nav .sidenav-trigger {
            float: unset;
            position: unset;
            margin: 0;
            height: 5vh;
            line-height: 5vh;
        }
        nav .sidenav-trigger i {
            padding: 0 2.5vw;
            height: 5vh;
            line-height: 5vh;
            color: #fff;
            background-color: var(--primary-color);
        }
        .sidenav li > a,
        .sidenav li > .link {
            color: #24262b;
            display: block;
            font-size: 1rem;
            height: 5vh;
            line-height: 5vh;
            padding: 0 32px;
        }
        .sidenav li > a > i {
            margin-right: 2vw;
        }
        nav i.material-icons {
            font-size: 2rem;
        }

        .mobileNavRight {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .themeTextColor {
            color: var(--primary-color);
        }
        .shoppingCartIconContainer {
            position: relative;
        }
        .mobileShoppingIcon {
            height: 5vh;
            line-height: 6vh;
            font-size: 1.7rem !important;
        }
        .mg-rt-2 {
            margin-right: 2vw;
        }
        .iconLinks .notify {
            height: 1.8vh;
            line-height: 1.8vh;
            font-size: 0.5rem;
            padding: 0 1vw;
            border-radius: 50%;
            position: absolute;
            right: 3.8vw;
            bottom: 2.5vh;
        }
        .sidenav li > a.mobileWishist {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .sidenav li > a.mobileWishist i {
            position: relative;
            top: 0.4vh;
            font-size: 1.6rem;
            margin-right: 2vw;
        }

        .mobileSidNavBrand,
        .sidenav li.mobileSidNavBrand > a {
            height: 10vh;
            line-height: 10vh;
        }
        .sidenav li.mobileSidNavBrand > a {
            background-color: var(--primary-color);
            color: black;
            font-size: 1.4rem;
            font-weight: 500;
        }

        .sidenav li > .input-field .prefix {
            position: absolute;
            width: 2.4rem;
            font-size: 1.6rem;
            -webkit-transition: color 0.2s;
            transition: color 0.2s;
            top: 0.5rem;
            height: 2rem;
            line-height: 2rem;
        }
        .sidenav li > .input-field {
            padding: 0 8.6vw;
            margin: 0;
        }
        .sidenav li > .input-field .prefix ~ input {
            margin-left: 2.4rem;
            width: calc(100% - 2.4rem);
        }

        input[type="text"]:not(.browser-default) {
            height: 2rem;
        }
        input[type="text"]:not(.browser-default):focus:not([readonly]) {
            border-bottom: thin solid var(--primary-color);
            box-shadow: unset;
        }
        .sidenav li > .input-field .prefix.active {
            color: var(--primary-color);
        }
    }
</style>
