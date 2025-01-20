import { createRouter, createWebHistory } from 'vue-router'

import Login from "../modules/firebase/login.vue";
import Register from "../modules/firebase/Register.vue";
import Feed from "../modules/firebase/Feed.vue";
import {getAuth, onAuthStateChanged, getIdToken} from "firebase/auth";
import Home from "../views/Home.vue";
import RobProfile from "../modules/Rob/RobProfile.vue";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/feed',
        name: 'feed',
        component: Feed,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/rob-profile/:robId',
        name: 'robProfile',
        component: RobProfile,
        props: true,
        meta: {
            requiresAuth: true
        }
    }

]


const router = createRouter({
    history: createWebHistory(),
    routes
})

const getCurrentUser = () => {
    return new Promise((resolve, reject) => {
        const removeListener = onAuthStateChanged(
            getAuth(),
            async (user) => {
                if (user) {
                    const idToken = await getIdToken(user);
                    localStorage.setItem('token', idToken)
                }
                removeListener()
                resolve(user)
        },
            reject
        )
    })
}
router.beforeEach(async (to, from, next) => {
    if(to.matched.some((record) => record.meta.requiresAuth)){
        if(await getCurrentUser()){
            next()
        } else {
            alert("You don't have access !")
            next("/register")
        }
    }else {
        next()
    }
})

export default router