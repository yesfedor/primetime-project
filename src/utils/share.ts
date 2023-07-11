export const Share = {
  vk: (purl: string, ptitle: string, pimg: string, text: string) => {
    let url = 'https://vk.com/share.php?'
    url += 'url=' + encodeURIComponent(purl)
    url += '&title=' + encodeURIComponent(ptitle)
    url += '&description=' + encodeURIComponent(text)
    url += '&image=' + encodeURIComponent(pimg)
    url += '&noparse=true'
    Share.popup(url)
  },
  telegram: (purl: string, text: string) => {
    const url = `https://t.me/share/url?url=${purl}&text=${text}`
    Share.popup(url)
  },
  twitter: (purl: string, ptitle: string) => {
    let url = 'https://twitter.com/share?'
    url += 'text=' + encodeURIComponent(ptitle)
    url += '&url=' + encodeURIComponent(purl)
    url += '&counturl=' + encodeURIComponent(purl)
    Share.popup(url)
  },

  popup: (url: string) => {
    window.open(url, '', 'toolbar=0,status=0,width=626,height=436')
  },
}
