<template>
    <div>
        <nav>
            <div class="nav-wrapper hide-on-med-and-down">
                <div class="navRight">
                    <a href="/" class="brandLogo">
                        <img src="https://via.placeholder.com/47x47" alt="" />
                        <span class="brandname">{{ brandname }}</span>
                    </a>

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
                                <a
                                    href="#productCategorySection"
                                    v-if="loggedIn || editFlag != null"
                                    >{{ category.name }}</a
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
                                    v-else
                                    >{{ category.name }}</router-link
                                >
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
                <div class="navRight">
                    <ul class="navRight">
                        <li>
                            <a
                                class="iconLinks modal-trigger"
                                href="#searchModal"
                                ><i class="material-icons">search</i></a
                            >
                        </li>
                        <li>
                            <a
                                :href="!isAuthenticated ? `/auth/signin` : `#`"
                                :class="classObject"
                                data-target="authDropdown"
                            >
                                <i class="material-icons">person_outline</i>
                            </a>
                        </li>
                        <ul
                            id="authDropdown"
                            v-if="isAuthenticated"
                            class="dropdown-content"
                        >
                            <li>
                                <a
                                    :href="
                                        role == `Admin`
                                            ? `/vendor/dashboard`
                                            : `/your_account/dashboard`
                                    "
                                    >{{ names }}</a
                                >
                            </li>
                            <li><a href="#!" @click="logout">Logout</a></li>
                        </ul>
                        <li>
                            <a href="#" class="iconLinks withBadge">
                                <i class="material-icons">favorite_border</i>
                                <span class="notify">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="iconLinks withBadge">
                                <i class="material-icons">shopping_cart</i>
                                <span class="notify">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="nav-wrapper hide-on-large-only">
                <a href="#" data-target="mobile-nav" class="sidenav-trigger"
                    ><i class="material-icons">menu</i></a
                >
                <a href="#!" class="brand-logo">
                    <img src="https://via.placeholder.com/47x47" alt="" />
                    <span class="brandname">{{ brandname }}</span>
                </a>

                <span class="mobileIconLinks">
                    <a
                        :href="!isAuthenticated ? `/auth/signin` : `#`"
                        :class="classObject"
                        data-target="authDropdown"
                    >
                        <i class="material-icons">person_outline</i>
                        <ul
                            id="authDropdown"
                            v-if="isAuthenticated"
                            class="dropdown-content"
                        >
                            <li>
                                <a
                                    :href="
                                        role == `Admin`
                                            ? `/vendor/dashboard`
                                            : `/your_account/dashboard`
                                    "
                                    >{{ names }}</a
                                >
                            </li>
                            <li><a href="#!" @click="logout">Logout</a></li>
                        </ul>
                    </a>
                    <a href="" class="iconLinks withBadge">
                        <i class="material-icons">shopping_cart</i>
                        <span class="notify">0</span>
                    </a>
                </span>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-nav">
            <li class="mobileSidNavBrand">
                <a href="/" class="brandLogo">
                    <img src="https://via.placeholder.com/47x47" alt="" />
                    <span class="brandname">{{ brandname }}</span>
                </a>
            </li>
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
                <a class="modal-trigger" href="#searchModal"> Search </a>
            </li>
            <li>
                <a href="#"> Blog </a>
            </li>
            <li>
                <a :href="mailUs">Contact Us</a>
            </li>
            <li>
                <a
                    :href="!isAuthenticated ? `/auth/signin` : `#`"
                    :class="classObject"
                    data-target="authDropdownSmall"
                >
                    <i class="material-icons">person_outline</i>
                </a>
                <ul
                    id="authDropdownSmall"
                    v-if="isAuthenticated"
                    class="dropdown-content"
                >
                    <li>
                        <a
                            :href="
                                role == `Admin`
                                    ? `/vendor/dashboard`
                                    : `/your_account/dashboard`
                            "
                            >{{ names }}</a
                        >
                    </li>
                    <li><a href="#!" @click="logout">Logout</a></li>
                </ul>
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
                            <option value="0">All Categories</option>
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
    import fetchData from "@/mixin/apiMixin";
    import { useCartStore } from "@/store/store.js";
    export default {
        mixins: [fetchData],
        data() {
            return {
                searchproduct: {
                    categoryId: 0,
                    productName: "",
                },
                editFlag: null,
            };
        },
        mounted() {
            localStorage.setItem("previousPage", this.$route.fullPath);
            this.editFlag = localStorage.getItem("editFlag");

            var elems = document.querySelectorAll(".sidenav");
            this.sideNavInstance = M.Sidenav.init(elems, {
                edge: "left",
            });

            var elems = document.querySelectorAll(".modal");
            this.serchModalViewState = M.Modal.init(elems, {
                endingTop: "5%",
            });
        },
        updated() {
            var dropdown1 = document.querySelectorAll(
                ".dropdown-trigger"
            );
            // var dropdown2 = document.querySelectorAll(".authDropdownPhone")
            var dropdownOptions = {
                closeOnClick: true,
            };
            if (window.innerWidth < 768) {
                // This is a mobile screen (assuming 768px is the breakpoint for mobile)
               dropdownOptions.hover = false;
            } else {
                // This is medium-sized and above
                dropdownOptions.hover = true;
                dropdownOptions.alignment = "right";
            }
            M.Dropdown.init(dropdown1, dropdownOptions);

        },
        computed: {
            classObject() {
                return {
                    iconLinks: true,
                    'dropdown-trigger': this.isAuthenticated 
                }
            },
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
                    this.$emit("showEditNavMenu", true);
                    console.log("am here");
                }
            },
            async logout() {
                const response = await this.fetchData("/auth/logout", 3, "post");
                if (response.status === 200) {
                    if (response.data.status == 401) {
                        location.reload();
                    }
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
    .noMarginBtm {
        margin-bottom: 0 !important;
    }
    nav {
        background-color: #ffffff;
        height: 10vh;
        line-height: 10vh;
    }
    .nav-wrapper {
        width: 100%;
        height: 10vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1vh 1vw;
    }
    .navRight {
        display: flex;
        align-items: center;
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
    }
    ul.navLinks li a,
    ul li .link {
        padding: 0 0.7vw;
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
    ul li a.iconLinks.withBadge {
        display: flex;
        padding-right: 0;
    }
    ul li a.authLink {
        margin-right: 2vw;
    }
    ul li a:hover,
    ul li .link:hover {
        color: var(--primary-color);
    }
    .iconLinks .notify {
        background-color: var(--primary-color);
        color: #ffffff;
        font-size: 0.6rem;
        padding: 0 0.4vw;
        border-radius: 50%;
        position: relative;
        right: 0.9vw;
        height: 1.8vh;
        line-height: 1.8vh;
        top: 4.5vh;
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

    #searchModal .searchFields {
        display: grid;
        grid-template-columns: 2fr 4fr 0.5fr;
    }
    #searchModal .input-field {
        margin: 0;
    }
    #searchModal .input-field input {
        width: 100%;
        outline: unset;
        padding: 1.45vh;
        border: thin solid #f1eded;
    }

    #searchModal a.searchBtn {
        height: 6.1vh;
        line-height: 6.6vh;
        background-color: var(--primary-color);
    }
    #searchModal a.searchBtn.btn i {
        font-size: 1rem;
    }
    #searchModal a.searchBtn:hover {
        color: #94959e;
    }

    .closeModal {
        position: relative;
        top: -2.5vh;
        right: 0vw;
    }

    /* TODO: TABLET */

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        nav {
            height: 7.5vh;
            line-height: 7.5vh;
        }
        .nav-wrapper {
            width: 100%;
            height: 100%;
            padding: 1vh 2vw;
        }
        nav a,
        nav .brand-logo {
            color: #24262b;
        }
        nav .brand-logo {
            position: unset;
            display: flex;
            align-items: center;
            webkit-transform: unset;
            transform: unset;
            font-size: 1.7rem;
            font-weight: 500;
        }

        nav a.iconLinks.withBadge,
        .mobileIconLinks {
            display: flex;
            /* padding-right: 0; */
        }
        nav a.iconLinks {
            padding: 0 1.3vw;
        }
        .iconLinks .notify {
            font-size: 0.5rem;
            padding: 0 0.9vw;
            right: 3vw;
            height: 1.5vh;
            line-height: 1.5vh;
            top: 3.5vh;
        }
        nav .brand-logo img,
        .sidenav .brandLogo img {
            width: 11vw;
        }
        .sidenav .brandLogo .brandname {
            color: var(--primary-color);
        }
        .sidenav li {
            border-bottom: thin solid #e7e7e7;
        }
        .sidenav li > a,
        .sidenav li > .link {
            color: #24262b;
            /* display: block; */
            /* font-size: 1rem; */
            height: 5vh;
            line-height: 5vh;
            padding: 0 32px;
        }
        .sidenav li.mobileSidNavBrand > a {
            height: 10vh;
            line-height: 10vh;
        }
        .sidenav li > a > i.material-icons {
            background-color: var(--primary-color);
            height: 100%;
            width: 5vh;
            text-align: center;
            margin-right: 2vw;
        }

        .modal {
            width: 97%;
        }
        .modal .modal-content {
            padding: 3vh 0;
        }
        #searchModal .searchFields {
            grid-template-columns: 2.5fr 4fr 0.8fr;
        }
        #searchModal .input-field input {
            padding: 1.2vh;
        }
        #searchModal a.searchBtn {
            height: 5vh;
            line-height: 5vh;
            padding: 0;
        }
    }
</style>