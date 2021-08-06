import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
Vue.use(Router)

//admin only
import home from './components/pages/Home'
import tags from './admin/pages/Tags'
import category from './admin/pages/Category'
import AdminUsers from './admin/pages/AdminUsers'
import Login from './admin/pages/Login'
import Role from './admin/pages/Role'
import AssignRole from './admin/pages/AssignRole'
import Blog from './admin/pages/Blog'
import EditBlog from './admin/pages/EditBlog'

const routes = [
	{
		path: '/',
		component: home,
		name: 'home',
	},
	{
		path: '/tags',
		component: tags,
		name: 'tags'
	},
	{
		path: '/category',
		component: category,
		name: 'category'

	},
	{
		path: '/admin-users',
		component: AdminUsers,
		name: 'admin-users'

	},
	{
		path: '/login',
		component: Login,
		name: 'login'

	},
	{
		path: '/role',
		component: Role,
		name: 'role'

	},
	{
		path: '/asign-role',
		component: AssignRole,
		name: 'asign-role'

	}
	,
	{
		path: '/blog',
		component: Blog,
		name: 'blog'

	},
	{
		path: '/edit-blog?blog_id=:blog_id',//(1)
		component: EditBlog,
		name: 'edit-blog'

	},
	{
		path :'*',
		component: home,

	},

]

export default new Router({
	mode: 'history',
	routes
})

/*
Note
 */
//(1)vue-router params: https://stackoverflow.com/a/45298071/11297747