import { createRouter, createWebHistory } from "vue-router";
//user
import User from "./components/User.vue";
import Admin from "./components/Admin.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Home from "./components/Home.vue";
import Clothing from "./components/Clothing.vue";
import Product from "./components/Product.vue";
import ResultSearch from "./components/ResultSearch.vue";
import ResultFilter from "./components/ResultFilter.vue";
import ProductDetails from "./components/ProductDetails.vue";
import Contact from "./components/Contact.vue";
//--admin
import AdminLogin from "./components/admin/Login.vue";
import Dashboard from "./components/admin/Dashboard.vue";
//----category
import Categories from "./components/admin/category/Categories.vue";
import AddCategory from "./components/admin/category/AddCategory.vue";
import EditCategory from "./components/admin/category/EditCategory.vue";
//----end category

//----product
import Products from "./components/admin/product/Products.vue";
import AddProduct from "./components/admin/product/AddProduct.vue";
import EditProduct from "./components/admin/product/EditProduct.vue";
//----end product

//----slide
import Slides from "./components/admin/slide/Slides.vue";
import AddSlide from "./components/admin/slide/AddSlide.vue";
import EditSlide from "./components/admin/slide/EditSlide.vue";
//----end slide

//voucher
import Vouchers from "./components/admin/voucher/Vouchers.vue";
import AddVoucher from "./components/admin/voucher/AddVoucher.vue";
import EditVoucher from "./components/admin/voucher/EditVoucher.vue";
//end voucher
//--end admin
const routes = [
  {
    path: "/",
    component: User,
    name: "User",
    children: [
      {
        path: "login",
        name: "Login",
        component: Login,
        meta: { public: true },
      },
      {
        path: "register",
        name: "Register",
        component: Register,
        meta: { public: true },
      },
      {
        path: "",
        name: "Home",
        component: Home,
        meta: { public: true },
      },
      {
        path: "clothing/:name",
        name: "Clothing",
        component: Clothing,
        meta: { public: true },
      },
      {
        path: "clothing/:name/:cate_id",
        name: 'Product',
        component: Product,
        meta: { public: true },
      },
      {
        path: "search",
        name: 'ResultSearch',
        component: ResultSearch,
        meta: { public: true },
      },
      {
        path: "filter",
        name: 'ResultFilter',
        component: ResultFilter,
        meta: { public: true },
      },
      {
        path: "product/details/:id",
        name: 'ProductDetails',
        component: ProductDetails,
        meta: { public: true, requiresAuth: true },
      },
      {
        path: "contact",
        name: "Contact",
        component: Contact,
        meta: { public: true },
      },
    ],
  },
  {
    path: "/admin",
    component: Admin,
    children: [
      {
        path: "",
        name: "Dashboard",
        component: Dashboard,
        meta: { public: false, role: 1 },
      },
      //categories
      {
        path: "categories",
        name: "Categories",
        component: Categories,
        meta: { public: false, role: 1 },
      },
      {
        path: "category/add",
        name: "AddCategory",
        component: AddCategory,
        meta: { public: false, role: 1 },
      },
      {
        path: "category/edit/:id",
        name: "EditCategory",
        component: EditCategory,
        meta: { public: false, role: 1 },
      },
      //end categories
      //start products
      {
        path: "products",
        name: "Products",
        component: Products,
        meta: { public: false, role: 1 },
      },
      {
        path: "product/add",
        name: "AddProduct",
        component: AddProduct,
        meta: { public: false, role: 1 },
      },
      {
        path: "product/edit/:id",
        name: "EditProduct",
        component: EditProduct,
        meta: { public: false, role: 1 },
      },
      //end products
      //start slides
      {
        path: "slides",
        name: "Slides",
        component: Slides,
        meta: { public: false, role: 1 },
      },
      {
        path: "slide/add",
        name: "AddSlide",
        component: AddSlide,
        meta: { public: false, role: 1 },
      },
      {
        path: "slide/edit/:id",
        name: "EditSlide",
        component: EditSlide,
        meta: { public: false, role: 1 },
      },
      //end slides
      //start vouchers
      {
        path: "vouchers",
        name: "Vouchers",
        component: Vouchers,
        meta: { public: false, role: 1 },
      },
      {
        path: "voucher/add",
        name: "AddVoucher",
        component: AddVoucher,
        meta: { public: false, role: 1 },
      },
      {
        path: "voucher/edit/:id",
        name: "EditVoucher",
        component: EditVoucher,
        meta: { public: false, role: 1 },
      },
      //end vouchers
    ],
  },
  {
    path: "/admin/login",
    name: "AdminLogin",
    component: AdminLogin,
    meta: { public: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global Middleware
router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem("user"));
  const token = localStorage.getItem("token");

  //điều hướng đến /admin/login, nếu đã login với role===1 thì về /admin
  if (to.path === "/admin/login" && token && user && user.vaitro === 1) {
    return next("/admin");
  }
  //điều hướng đến /admin/login, nếu đã login với role===1 thì về / (trang chủ)
  if (to.path === "/admin/login" && token && user && user.vaitro === 0) {
    return next("/");
  }
  //điều hướng đến /register, nếu đã đăng nhập thì về / (trang chủ)
  if (to.path === "/register" && token && user) {
    return next("/");
  }
  //điều hướng đến /login, nếu đã đăng nhập thì về / (trang chủ)
  if (to.path === "/login" && token && user) {
    return next("/");
  }
  if (to.path === "/login" && token && user && user.vaitro===1) {
    return next("/admin");
  }
  //điều hướng đến bất cứ trang quản lí Admin
  if (to.matched.some((record) => record.meta.role === 1)) {
    //chưa đăng nhập hoặc không có quyền thì quay về /login
    if (!token || !user || user.vaitro !== 1) {
      return next({ name: "Login" });
    }
  }

  if (to.matched.some(record => record.meta.requiresAuth) && !token) {
      next({ name: 'Login' });
  }

  next();
});

export default router;
