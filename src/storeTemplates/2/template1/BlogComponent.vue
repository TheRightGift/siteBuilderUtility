<template>
  <div>
    <div class="container">
      <div class="row">
        <h3 class="blogHeader">Blogs</h3>
        <div class="col s12 l3 m4 mb-4" v-for="blog in blogSeeder" :key="blog">
          <img :src="blog.images[0].url" alt="Blog post image" class="responsive-img"/>
          <p class="blog-description">February 03, 2022</p>
          <h6 class="blog_description">
            {{ blog.title.slice(0, 30) }}..., <br />
            {{ blog.category.name }}
          </h6>

          <router-link :class="classObject" :to=" loggedIn ? `#!` : { name: `blog-detail`, params: { blog: blog.title, }, query: { additionalData: blog.id ?? `blog_id` } }">
            Read more
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.mb-4 {
  margin-bottom: 4vh;
}
.btn {
  background-color: var(--primary-color);
  box-shadow: none;
  color: #fff;
}
.btn:hover {
  background-color: #fff;
  border: 1px solid var(--primary-color);
  color: var(--primary-color);
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
</style>
<script>
import templateMixin from "@/mixin/templateMixin";
export default {
  computed: {
    classObject() {
      return {
        "waves waves-effect": !this.loggedIn && this.editFlag !== `1`,
        "btn btn-blog": this.showBlogButton,
      };
    },
  },
  mixins: [templateMixin],
  data() {
    return {
      showBlogButton: true,
      editFlag: null,
      blogSeeder: [
        {
          images: [
            {
              url: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/blog/blog4.png",
            },
          ],
          category_id: 1,
          category: {
            name: "Women",
          },
          created_at: "2023-09-28T04:41:10.000000Z",
          title:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sollicitudin non purus finibus commodo. Etiam at dui quis nulla auctor commodo eu convallis orci",
          body: "Fashiion Trends in 2021 Styles, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo",
        },
        {
          images: [
            {
              url: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/blog/blog3.png",
            },
          ],
          category_id: 1,
          category: {
            name: "Men",
          },
          created_at: "2023-09-28T04:41:10.000000Z",
          title:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sollicitudin non purus finibus commodo. Etiam at dui quis nulla auctor commodo eu convallis orci",
          body: "Fashiion Trends in 2021 Styles, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo",
        },
        {
          images: [
            {
              url: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/blog/blog2.png",
            },
          ],
          category_id: 1,
          category: {
            name: "Kids",
          },
          created_at: "2023-09-28T04:41:10.000000Z",
          title:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sollicitudin non purus finibus commodo. Etiam at dui quis nulla auctor commodo eu convallis orci",
          body: "Fashiion Trends in 2021 Styles, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo",
        },
        {
          images: [
            {
              url: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/blog/blog1.png",
            },
          ],
          category_id: 1,
          category: {
            name: "Accessories",
          },
          title:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sollicitudin non purus finibus commodo. Etiam at dui quis nulla auctor commodo eu convallis orci",
          created_at: "2023-09-28T04:41:10.000000Z",
          body: "Fashiion Trends in 2021 Styles, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo",
        },
      ],
    };
  },
  mounted() {
    this.editFlag = localStorage.getItem("editFlag");
    if (this.blogs.length > 0) {
      this.blogSeeder = this.blogs;
    }
  },
};
</script>
