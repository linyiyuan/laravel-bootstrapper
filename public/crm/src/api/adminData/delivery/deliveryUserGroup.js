import request from '@/utils/request'
export function deliveryUserGroupList(params) {
  return request({
    url:'/admin_data/delivery/delivery_user_group',
    method:'get',
    params:params
  })
}

export function createDeliveryUserGroup(params) {
  return request({
    url:'/admin_data/delivery/delivery_user_group',
    method:'post',
    data:params
  })
}

export function updateDeliveryUserGroup(id, params) {
  return request({
    url:'/admin_data/delivery/delivery_user_group' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteDeliveryUserGroup(id) {
  return request({
    url:'/admin_data/delivery/delivery_user_group' + '/' + id,
    method:'delete',
    data:id,
  })
}
