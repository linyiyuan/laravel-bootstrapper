import request from '@/utils/request'
export function getDataPermission(params) {
  return request({
    url:'/setting/auth/data_permission',
    method:'get',
    params:params
  })
}

export function createDataPermission(params) {
  return request({
    url:'/setting/auth/data_permission',
    method:'post',
    data:params
  })
}

export function deleteDataPermission(id) {
  return request({
    url:'/setting/auth/data_permission' + '/' + id,
    method:'delete',
    data:id,
  })
}

export function updateDataPermission(id, params) {
  return request({
    url:'/setting/auth/data_permission' + '/' + id,
    method:'put',
    data:params
  })
}

export function giveRoleDataPermission(data) {
  return request({
    url:'/setting/auth/give_role_data_permission',
    method:'post',
    data:data,
  })
}

export function giveUserDataPermission(data) {
  return request({
    url:'/setting/auth/give_user_data_permission',
    method:'post',
    data:data,
  })
}
