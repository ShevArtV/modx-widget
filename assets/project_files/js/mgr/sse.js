class Sse {
  constructor() {
    if (window.Sse) return window.Sse;
    this.initialize();
    window.Sse = this;
  }

  initialize() {
    this.eventSource = new EventSource('/assets/project_files/php/ssehandler.php');
    this.eventSource.onmessage = (e) => {
      const data = JSON.parse(e.data);
      if (data.error) {
        console.log(data.error);
        this.eventSource.close();
      } else {
        if (data.eventName) {
          document.dispatchEvent(new CustomEvent(data.eventName, {
            bubbles: true,
            cancelable: false,
            detail: {
              data: data,
            }
          }))
        }
      }
    }
  }
}
document.addEventListener('si:init', () => {
  new Sse();
})
