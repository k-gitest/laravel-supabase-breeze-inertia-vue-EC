import { defineComponent, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrIncludeBooleanAttr, ssrLooseContain } from "vue/server-renderer";
import { usePage, useForm } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "UpdateNewsletterSubscribe",
  __ssrInlineRender: true,
  props: {
    mustVerifyEmail: {},
    status: {}
  },
  setup(__props) {
    const user = usePage().props.auth.user;
    const form = useForm({
      subscribed: user.subscribed ?? false
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(_attrs)}><header><h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">MailMagazine Subscribe</h2><p class="mt-1 text-sm text-gray-600 dark:text-gray-400"> メールマガジン購読を選択して下さい </p></header><div class="flex flex-col gap-2"><form><label for="magazine"><p class="text-sm">購読チェック</p><input type="checkbox"${ssrIncludeBooleanAttr(Array.isArray(unref(form).subscribed) ? ssrLooseContain(unref(form).subscribed, null) : unref(form).subscribed) ? " checked" : ""} id="magazine" class="toggle"></label><div><button type="submit" class="btn">送信</button></div></form></div></section>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Profile/Partials/UpdateNewsletterSubscribe.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
