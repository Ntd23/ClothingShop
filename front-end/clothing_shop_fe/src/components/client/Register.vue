<template>
  <div>
    <form>
      <div class="mb-3">
        <label class="form-label">Tên đăng nhập <span class="required">*</span></label>
        <input type="text" class="form-control" />
      </div>
      <div class="mb-3">
        <label class="form-label">Số điện thoại <span class="required">*</span></label>
        <input type="text" class="form-control" />
      </div>
      <div class="mb-3">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="text" class="form-control" />
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu<span class="required">*</span></label>
        <input type="password" class="form-control" />
      </div>
      <div class="col-mb-3" style="text-align: right">
        <span><a href="" data-bs-toggle="modal" data-bs-target="#loginModal"
            style="margin-right: 134px; font-size: 10px">Bạn đã có tài khoản?</a></span>
        <button type="button" class="btn btn-ok" style="margin-right: 10px">
          Đăng ký
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Bỏ qua
        </button>
      </div>
    </form>
  </div>
</template>
