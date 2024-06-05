import { defineComponent, unref, withCtx, createVNode, withDirectives, toDisplayString, vShow, openBlock, createBlock, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate } from "vue/server-renderer";
import { usePage, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import { _ as _sfc_main$2 } from "./CartTableBody-DmWkNu-v.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CartIndex" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Cart</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Cart")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div>`);
            if (unref(props).data) {
              _push2(`<div class="overflow-x-auto"${_scopeId}><table class="table"${_scopeId}><thead${_scopeId}><tr${_scopeId}><th${_scopeId}>商品名</th><th${_scopeId}>数量</th><th${_scopeId}>料金</th><th${_scopeId}></th></tr></thead>`);
              _push2(ssrRenderComponent(_sfc_main$2, {
                carts: unref(props).data
              }, null, _parent2, _scopeId));
              _push2(`</table></div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              withDirectives(createVNode("div", null, [
                createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
              ], 512), [
                [vShow, unref(props).flash.success]
              ]),
              unref(props).data ? (openBlock(), createBlock("div", {
                key: 0,
                class: "overflow-x-auto"
              }, [
                createVNode("table", { class: "table" }, [
                  createVNode("thead", null, [
                    createVNode("tr", null, [
                      createVNode("th", null, "商品名"),
                      createVNode("th", null, "数量"),
                      createVNode("th", null, "料金"),
                      createVNode("th")
                    ])
                  ]),
                  createVNode(_sfc_main$2, {
                    carts: unref(props).data
                  }, null, 8, ["carts"])
                ])
              ])) : createCommentVNode("", true)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/CartIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
