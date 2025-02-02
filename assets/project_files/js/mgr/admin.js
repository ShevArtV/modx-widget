const admin = {
  syncBtn: document.querySelector('[data-si-preset="sync_bitrix"]')
}

document.addEventListener('si:init', () => {
  if(SendIt.getCookie('sync_bitrix') === '1'){
    admin.syncBtn && (admin.syncBtn.disabled = true);
  }
})

document.addEventListener('sync:bitrix:finished', (e) => {
  SendIt.Notify.success(e.detail.data.message);
  admin.syncBtn && (admin.syncBtn.disabled = false);
  SendIt.setCookie('sync_bitrix', '0');
})

document.addEventListener('si:send:before', (e) => {
  const {fetchOptions, target} = e.detail;
  if(fetchOptions.headers['X-SIPRESET'] === 'sync_bitrix') {
    admin.syncBtn && (admin.syncBtn.disabled = true);
    SendIt.setCookie('sync_bitrix', '1');
  }
})
