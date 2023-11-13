<template>
    <div class="">
        <div class="topbar hide-on-med-and-down">
            <div class="container">
                <div class="topbar-content">
                    <a href="#" class="welcome">{{branddesc == "" ? "Welcome to Worldwide Electronics Store" : branddesc}}</a>
                    <div class="flex-right">
                        <!-- <a href="#">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Store Locator</span>
                        </a>
                        <div class="divider"></div> -->
                        <!-- <a href="#">
                            <i class="fa-solid fa-truck-fast"></i>
                            <span>Track your order</span>
                        </a> -->
                        <!-- <div class="divider"></div> -->
                        <a href="#">
                            <i class="fa-solid fa-dollar-sign"></i>
                            <span>Dollar (US)</span>
                        </a>
                        <div class="divider"></div>
                        <a href="/auth/signin" v-if="!isAuthenticated">
                            <i class="fa-regular fa-user"></i>
                            <span>Register <span class="or">or</span> Sign in</span>
                        </a>
                        <a v-else :href=" role == `Admin` ? `/vendor/dashboard` : `/your_account/dashboard`">{{ names }}</a>
                    </div>
                </div>
            </div>
        </div>
        
        <nav class="hide-on-med-and-down">
            <div class="container">
                <div class="nav-wrapper">
                    <div class="flexed">
                        <div class="flexed gap-7">
                            <a href="#" class="logo">{{ brandname }}</a>
                            <ul>
                                <span v-show="categories.length < 6">
                                    <li v-for="category in categories" :key="category.id" @click="showCategoryEditEditor">
                                        <router-link :to=" loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
                                            {{ category.name }}
                                        </router-link>
                                    </li>
                                </span>
                                <li v-show="categories.length > 6">
                                    <a class="dropdown-trigger" href="#productCatSectionInner" data-target="dropdownCategory">Categories</a>
                                </li>
                                <ul id="dropdownCategory" class="dropdown-content">
                                    <li v-for="category in categories" :key="category.id" @click="showCategoryEditEditor">
                                        <router-link :to=" loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category`, }, query: { additionalData: category.id ?? `category_id`} }">
                                            {{ category.name }}
                                        </router-link>
                                    </li>
                                </ul>
                                <li>
                                    <a href="#!">Blog</a>
                                </li>
                            </ul>
                            <!-- <a href="#" data-target="slide-out" class="trigger sidenav-trigger"><i class="material-icons">menu</i></a> -->
                        </div>
                        <form>
                            <div class="search-container">
                                <input type="search" class="search-input browser-default" placeholder="Search for Products"/>
                                <select class="search-select browser-default">
                                    <option value="option1">All Categories</option>
                                    <option :value="category.id" v-for="category in categories" :key="category.id">{{ category.name }}</option>
                                </select>
                                <div class="searchIcon"><i class="fa-solid fa-magnifying-glass"></i></div>
                            </div>
                        </form>
                        <div class="flexed gap-2">
                            <a href="/your_account/favorites"><i class="fa-solid fa-heart"></i></a>
                            <a href="/auth/signin" v-if="!isAuthenticated"><i class="fa-regular fa-user"></i></a>
                            <a v-else :href="role == `Admin` ? `/vendor/dashboard` : `/your_account/dashboard`">{{ names }}</a>
                            <span class="relative cursor">
                                <router-link :to="{ name: `Cart` }"><i class="fa-solid fa-bag-shopping"></i></router-link>
                                <div class="badge-under fw-700">{{ cartCount }}</div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <nav class="hide-on-large-only">
            <div class="nav-wrapper">
                <a href="#" data-target="slide-out" class="sidenav-trigger"
                    ><i class="material-icons">menu</i></a
                >
                <a href="#" class="brand-logo">{{ brandname }}</a>
                <ul class="end">
                    <li>
                        <a href=""><i class="fa-solid fa-magnifying-glass"></i></a>
                    </li>
                    <li>
                        <a href="/auth/signin" v-if="!isAuthenticated"><i class="fa-regular fa-user"></i></a>
                        <a v-else :href="role == `Admin` ? `/vendor/dashboard` : `/your_account/dashboard`">{{ names }}</a>
                    </li>
                    <li class="relative cursor">
                        <router-link :to="{ name: `Cart` }"><i class="fa-solid fa-bag-shopping"></i></router-link>
                        <div class="badge-under fw-700">{{ cartCount }}</div>
                    </li>
                </ul>

                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <span v-show="categories.length < 3">
                        <li
                            v-for="category in categories"
                            :key="category.id"
                            @click="showCategoryEditEditor"
                        >
                            <router-link :to="loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
								{{ category.name }}
							</router-link>
                        </li>
                    </span>
                    <li v-show="categories.length > 3">
                        <a
                            class="dropdown-trigger"
                            href="#"
                            data-target="dropdownCategory"
                            >Categories</a
                        >
                    </li>
                    <ul id="dropdownCategory" class="dropdown-content">
                        <li
                            v-for="category in categories"
                            :key="category.id"
                            @click="showCategoryEditEditor"
                        >
							<router-link :to="loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
								{{ category.name }}
							</router-link>
                        </li>
                    </ul>
                    <li><a href="#!">Blog</a></li>
                </ul>
            </div>
        </nav>
        <ul id="slide-out" class="sidenav">
            <li>
                <a href="#" class="brand-logo">
                    {{ brandname }}
                </a>
            </li>
            <li
                v-for="category in categories"
                :key="category.id"
                @click="showCategoryEditEditor"
            >
				<router-link :to="loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
					{{ category.name }}
				</router-link>
            </li>
            <li><a class="waves-effect" href="#!">Blog</a></li>
        </ul>
    </div>
</template>
<script>
    import templateMixin from "@/mixin/templateMixin";

    export default {
        mixins: [templateMixin],
        data() {
            return {
                isOpen: false,
            };
        },
        mounted() {
            localStorage.setItem("previousPage", this.$route.fullPath);
            var dropdowns = document.querySelectorAll(".dropdown-trigger");
            for (var i = 0; i < dropdowns.length; i++) {
                M.Dropdown.init(dropdowns[i]);
            }
        },
    };
</script>

<style scoped>
    nav ul a:hover {
        background: none;
    }
    .sidenav li:first-child {
        padding: 4vh 0 0 0;
    }
    .sidenav li {
        padding: 0.4rem 0;
    }
    .collapsible-contents {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #fff;
        border-bottom-right-radius: 0.4375rem;
        border-bottom-left-radius: 0.4375rem;
        z-index: 1000;
    }
    .mainNav {
        padding: 2vh 0;
    }
    .mainNav ul li a {
        font-weight: 700;
    }

    .gap-2 {
        gap: 2vh;
    }
    .fw-700 {
        font-weight: 700;
    }
    .relative {
        position: relative;
    }
    .welcome,
    .or {
        font-size: 0.81288rem;
        color: #7c7c7c;
    }
    .welcome:hover {
        color: #333e48;
    }
    input[type="search"] {
        height: 100%;
        border: 1px solid var(--primary-color);
        border-top-left-radius: 6.1875rem;
        border-bottom-left-radius: 6.1875rem;
        padding-left: 2rem;
        outline: none;
        border-right: none;
    }
    input[type="search"]::placeholder {
        font-size: 0.938rem;
        color: #6b6b6b;
    }
    .gap-7 {
        gap: 7vh;
    }
    .search-container {
        display: flex;
        align-items: center;
        border-top-left-radius: 6.1875rem;
        border-bottom-left-radius: 6.1875rem;
        border: 2px solid var(--primary-color);
        height: 2.56288rem;
        border-top-right-radius: 6.1875rem;
        border-bottom-right-radius: 6.1875rem;
    }

    /* Style for the input field */
    .search-input {
        flex-grow: 1;
        border: none;
        padding: 10px;
    }
    .select-wrapper input.select-dropdown {
        border: 1px solid var(--primary-color) !important;
        border-bottom: 2px solid var(--primary-color) !important;
    }

    /* Style for the select element */
    .search-select {
        border: none;
        height: 100%;
        border-top: 1px solid var(--primary-color) !important;
        border-bottom: 1px solid var(--primary-color) !important;
        font-size: 0.93712rem;
        outline: none;
        /* margin-right: 1vh; */
    }

    select {
        color: #6b6b6b;
    }

    /* Style for the search icon */
    .searchIcon {
        background-color: var(--primary-color); /* Adjust the color as needed */
        color: #7c7c7c;
        padding: 0.5vh 2vh;
        height: 100%;
        line-height: 100%;
        cursor: pointer;
        text-align: center;
        border-top-right-radius: 6.1875rem;
        border-bottom-right-radius: 6.1875rem;
        display: flex;
        align-items: center;
    }
    .searchIcon i,
    nav .nav-wrapper i {
        font-size: 1.2rem;
        line-height: unset !important;
        height: unset;
    }
    .badge-under {
        position: absolute;
        background: #333e48;
        height: 1.37462rem;
        width: 1.37462rem;
        line-height: 1.37462rem;
        text-align: center;
        border-radius: 100%;
        color: #fff;
        font-size: 0.749rem;
        bottom: 20%;
        left: 55%;
    }
    @media only screen and (min-width: 1024px) {
        .topbar {
            border-bottom: 1px solid #7c7c7c38;
            padding: 1vh 0;
        }
        .topbar-content {
            display: flex;
            justify-content: space-between;
        }
        .container {
            width: 90%;
        }
        .flex-right {
            display: flex;
            align-items: center;
            gap: 3vh;
        }

        .flex-right a {
            font-size: 0.813rem;
            /* font-size: 1.12525rem; */
            color: #334141;
        }

        .flex-right i {
            padding-right: 1vh;
        }

        .divider {
            height: 2vh;
            width: 0.05vh;
            background-color: black;
        }
        nav {
            background-color: #fff;
            box-shadow: none;
            padding: 3vh 0;
            line-height: unset;
            height: unset;
        }
        nav .logo {
            width: 9.375rem;
        }
        nav a {
            color: #334141;
        }
        .flexed {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .nav-wrapper i {
            line-height: 1;
        }
        .trigger i {
            font-size: 1.8rem !important;
        }
        .trigger.sidenav-trigger {
            display: block !important;
        }
        .allDeptBtn {
            background-color: var(--primary-color);
            /* color: #333e48; */
            color: #FFF;
            padding: 1.5vh 4vh;
            font-weight: 700;
            border: none;
            font-size: inherit;
            border-top-right-radius: 1rem;
            border-top-left-radius: 1rem;
            font-family: "Open Sans", Helvetica, Arial, sans-serif;
        }
        .lg-h22 {
            height: 24.4vh;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 1023px) {
        nav .brand-logo {
            left: 12%;
        }
        nav {
            background-color: var(--primary-color);
            box-shadow: none;
            color: #333e48;
            height: 63px;
            line-height: 63px;
            padding: 2vh;
        }
        nav .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .sidenav-trigger {
            margin: 0;
            position: unset;
            left: 5%;
            color: #333e48;
        }

        nav ul a {
            padding: 0;
            color: #333e48;
        }

        .brand-logo {
            color: #333e48;
        }

        .end {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3vh;
        }

        .end i {
            font-size: 1.42rem;
            line-height: unset;
        }
    }

    @media only screen and (max-width: 767px) {
        nav .brand-logo {
            left: 25%;
        }
        nav {
            background-color: var(--primary-color);
            box-shadow: none;
            color: #333e48;
            height: 63px;
            line-height: 63px;
            padding: 2vh;
        }
        nav .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .sidenav-trigger {
            margin: 0;
            position: unset;
            left: 5%;
            color: #333e48;
        }

        nav ul a {
            padding: 0;
            color: #333e48;
        }

        .brand-logo {
            color: #333e48;
        }

        .end {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3vh;
        }

        .end i {
            font-size: 1.42rem;
            line-height: unset;
        }
    }
</style>
