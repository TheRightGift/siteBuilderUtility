<template>
    <div class="nav">
        <div class="navRight">
            <a href="/" class="brandLogo"><span class="brandname">{{ brandname }}</span></a>

            <ul class="navLinks">
                <li>
                    <a :href="loggedIn ? '#!' : '/'">Home</a>
                </li>
                <li
                    v-for="category in categories.slice(0, 3)"
                    :key="category.id"
                    @click="showCategoryEditEditor"
                >
                    <router-link :to="loggedIn ? '#!' : { name: 'product-search-category', params: { category_name: category.name ?? 'category' }, query: { additionalData: category.id ?? 'category_id' } }">{{
                        category.name
                    }}</router-link>
                </li>
                <li>
                    <a href="#">Blog</a>
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
                        href="/auth/signin"
                        class="authLink"
                        v-if="!isAuthenticated"
                        >LOGIN/REGISTER</a
                    >
                    <router-link v-else :to="role == 'Admin' ? '/vendor/dashboard' : '/your_account/dashboard'">{{ names }}</router-link>
                </li>
                <li>
                    <a
                        href="#"
                        class="iconLinks"
                        @click="displaySearchbar()"
                        ><i class="material-icons">search</i></a
                    >
                </li>
                <li>
                    <a href="#" class="iconLinks">
                        <i class="material-icons">favorite_border</i>
                        <span class="notify">{{ wishlistCount }}</span>
                    </a>
                </li>
                <li>
                    <router-link :to="{name: 'Cart'}" class="iconLinks">
                        <i class="material-icons">shopping_cart</i>
                        <span class="notify">{{ cartCount }}</span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
    <div v-if="showSearchbar" class="searchbar">
        <div class="input-field">
            <input id="search" type="search" v-model="searchData" />
            <label class="label-icon" for="search"
                ><i class="material-icons">search</i></label
            >
            <i class="material-icons" @click="displaySearchbar()">close</i>
        </div>
    </div>
</template>
  
  <script>
    import apiMixin from "../../../../apiMixin";
    import { useCartStore } from "../../../../store";
    export default {
        mixins: [apiMixin],
        data() {
            return {
                showSearchbar: false,
                searchData: "",
                brandname: 'StoreName'
            };
        },
        mounted() {
            localStorage.setItem("previousPage", this.$route.fullPath);
            this.fetchUser();
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
            mailUs(){
                return `mailto:${this.email}?subject=Contact%20Us&body=Hello%20Team,`;
            }
        },
        methods: {
            //   setActiveItem(item) {
            //     this.activeItem = item;
            //   },
            getCartNWishes() {
                const cartStore = useCartStore();
                cartStore.fetchCart();
                cartStore.fetchUserWishlists();
            },
            searchProducts() {
                // Add your search logic here
            },
            displaySearchbar() {
                this.showSearchbar = !this.showSearchbar;
            },
            showCategoryEditEditor() {
                if (this.loggedIn) {
                    this.$emit("showEditNavMenu", true);
                    console.log("am here");
                }
            },
            // #TODO: DO a mixin later for API
            async fetchUser() {
                try {
                    const response = await axios.get("/user");
                    const cartStore = useCartStore();
                    if (response.status === 200) {
                        if (response.data === "") {
                            cartStore.updateUser(false, null);
                        } else {
                            cartStore.updateUser(true, response.data);
                            this.getCartNWishes();
                        }
                    } else {
                        console.error(
                            `Error fetching user data. Status: ${response.status}`
                        );
                    }
                } catch (error) {
                    // Handle any other errors that might occur
                    console.error("Error fetching user data:", error);
                }
            },
        },
        props: {
            categories: Array,
            loggedIn: Boolean,
            email: String
        },
        watch: {},
    };
</script>
  
  <style scoped>
    .nav {
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
    ul.navLinks li a {
        padding: 0 0.7vw;
    }
    ul li a {
        text-decoration: none;
        text-transform: uppercase;
        color: #24262b;
        font-weight: 400;
        font-size: 1.1rem;
    }
    ul li a.iconLinks {
        padding: 0 1vw;
    }
    ul li a.authLink {
        margin-right: 2vw;
    }
    ul li a:hover {
        color: var(--primary-color);
    }
    .iconLinks .notify {
        background-color: var(--primary-color);
        color: #ffffff;
        font-size: 0.7rem;
        padding: 0 0.2vw;
        border-radius: 30%;
        position: relative;
        right: 0.9vw;
        bottom: 0.5vh;
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
</style>