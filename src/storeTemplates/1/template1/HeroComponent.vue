<template>
   
    <div class="parallax-container" :style="{zIndex: !loggedIn ? -1 : 'inherit'}">
        <div class="parallax" @click="showHeroEditor">
            <img
                :src="imageUrlWithTimestamp"
            />
        </div>
        <div class="heroContainer" @click="showHeroEditor">
            <div class="heroBackgroundOverlay"></div>
            <div class="heroContent">
                <h1 class="heroMainHeading">{{ heroSeeder.description }}</h1>
                <h3 class="heroMinorHeading">{{ heroSeeder.others }}</h3>
                <div class="heroCtaContainer">
                    <a href="#">Shop Now</a>
                    <a href="#">Find More</a>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {
        computed:{
            imageUrlWithTimestamp() {
                // Append the timestamp as a query parameter to the image URL
                return `${this.heroSeeder.image}?t=${this.timestamp}`;
            },
        },
        data() {
            return {
                heroSeeder: {
                    description: 'Raining Offers For Hot Summer!',
                    image: 'https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2019/12/home-new-bg-free-img.jpg',
                    others: '25% Off On All Products'
                },
                
            }
        },
        methods: {
            showHeroEditor() {
                if (this.loggedIn) {
                    this.$emit('showHeroEditor', true);
                }
            }
        },
        mounted() {},
        props: {
            loggedIn: Boolean,
            hero: Object,
            timestamp: Number
        },
        watch: {
            hero(newVal, oldVal) {
                if (Object.entries(newVal).length > 0) {
                    this.heroSeeder.description = newVal.description ?? this.heroSeeder.description;
                    this.heroSeeder.image = newVal.image ?? this.heroSeeder.image;
                    this.heroSeeder.others = newVal.others ?? this.heroSeeder.others;
                }
            }
        }
    };
</script>

<style scoped>
    .heroContainer {
        width: 100%;
        height: 100vh;
        padding: 35vh 23.4vw;
    }

    .heroBackgroundOverlay {
        width: 100%;
        height: 100vh;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0.49;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(180deg, #0084d6 0%, #000 100%);
    }

    .heroContent {
        position: relative;
        width: 60vw;
        height: 44.5vh;
        margin: 0 auto;
        color: #fff;
        z-index: 10;
    }

    .heroMainHeading {
        width: 50vh;
        margin: 0;
        font-size: 2.7rem;
        font-weight: 600;
    }

    .heroMinorHeading {
        font-size: 1.3rem;
        margin: 4vh 0 6vh 0;
    }

    .heroCtaContainer a {
        padding: 1vh 2vw;
        text-transform: uppercase;
        border: thin solid #fff;
    }

    .heroCtaContainer a:first-child {
        background-color: #fff;
        color: #000;
    }

    .heroCtaContainer a:first-child:hover {
        color: #fff;
        background-color: unset;
    }

    .heroCtaContainer a:last-child {
        color: #fff;
    }

    .heroCtaContainer a:last-child:hover {
        background-color: #fff;
        color: #000;
    }

    .parallax-container {
        height: 100vh;
    }
</style>
