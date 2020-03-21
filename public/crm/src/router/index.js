import Vue from 'vue'
import Router from 'vue-router'
import Layout from '../views/layout/Layout'

Vue.use(Router)

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
 **/
export const constantRouterMap = [
  { path: '/login', component: () => import('@/views/login/index'), hidden: true },

  {
    path: '',
    component: Layout,
    redirect: '/home',
    children: [{
        path: 'home',
        name: 'home',
        component: () => import('@/views/home/index'),
        meta: { title: '仪表盘', icon: 'home' }
      },
      { path: '/401', component: () => import('@/views/error/401'), hidden: true },
      { path: '/404', component: () => import('@/views/error/404'), hidden: true },

    ]
  },
]

export default new Router({
  // mode: 'history', //后端支持可开
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

/**
 * 需要过滤动态加载的路由
 **/
export const asyncRouterMap = [{
    path: '/admin_data',
    component: Layout,
    redirect: '/admin_data/delivery',
    name: 'Api:admin_data',
    meta: {
      title: '数据中心',
      icon: 'admin_data'
    },
    children: [{
      path: '/delivery',
      name: 'Api:admin_data/delivery',
      component: () => import('@/views/admin_data/index'),
      alwaysShow: true,
      meta: { title: '投放数据', icon: 'put_in_data' },
      children: [{
        path: 'put_in_data_user',
        name: 'Api:admin_data/delivery/delivery_user-index',
        component: () => import('@/views/admin_data/delivery/delivery_user/index'),
        meta: { title: '投放人员', icon: 'delivery_user' }
      }, ]
    }],
  },
  {
    path: '/setting',
    component: Layout,
    redirect: '/setting/auth',
    name: 'Api:setting',
    meta: {
      title: '设置模块',
      icon: 'product-list'
    },

    children: [{
        path: '/auth',
        name: 'Api:setting/auth',
        component: () => import('@/views/setting/index'), // Parent router-view
        meta: { title: '用户模块', icon: 'user' },
        children: [{
            path: 'user',
            name: 'Api:setting/auth/user-index',
            component: () => import('@/views/setting/user/users/index'),
            meta: { title: '角色列表', icon: 'product-list' }
          },
          {
            path: 'user/create',
            name: 'Api:setting/auth/user-store',
            component: () => import('@/views/setting/user/users/create'),
            meta: { title: ' 添加用户', icon: 'product-add' },
            hidden: true
          },
          {
            path: 'user/update',
            name: 'Api:setting/auth/user-update',
            component: () => import('@/views/setting/user/users/update'),
            meta: { title: '修改用户', icon: 'product-add' },
            hidden: true
          },
          {
            path: 'role',
            name: 'Api:setting/auth/role-index',
            component: () => import('@/views/setting/user/role/index'),
            meta: { title: '组权限', icon: 'role' },
          },
          {
            path: 'permission',
            name: 'Api:setting/auth/permission-index',
            component: () => import('@/views/setting/user/permission/index'),
            meta: { title: '功能权限', icon: 'permission' },
          },
          {
            path: 'data_permission',
            name: 'Api:setting/auth/data_permission-index',
            component: () => import('@/views/setting/user/data_permission/index'),
            meta: { title: '数据权限', icon: 'data_permission' },
          },
        ]
      },
      {
        path: '/system',
        name: 'Api:setting/system',
        component: () => import('@/views/setting/index'), // Parent router-view
        redirect: '/system/getConfig',
        meta: { title: '系统管理', icon: 'tools' },
        children: [{
            path: 'getConfig',
            name: 'Api:setting/system/get_config-getConfig',
            component: () => import('@/views/setting/system/index/'),
            meta: { title: '系统配置', icon: 'product-list' }
          },
          {
            path: 'getLog',
            name: 'Api:setting/system/get_operate_log-getList',
            component: () => import('@/views/setting/system/operateLog/'),
            meta: { title: '操作日志', icon: 'log' }
          },


        ]
      },


    ]

  },

  { path: '*', redirect: '/404', hidden: true }

]
