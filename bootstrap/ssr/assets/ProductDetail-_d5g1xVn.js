import { defineComponent, unref, withCtx, createVNode, createTextVNode, openBlock, createBlock, toDisplayString, withDirectives, vShow, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderStyle } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import { _ as _sfc_main$2 } from "./EcImageGallery-BXIFEkZy.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductDetail",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({
      product_id: props.data.id,
      user_id: props.auth.user.id,
      quantity: 1
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "ProductAllList" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>ProductDetail</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "ProductDetail")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b;
          if (_push2) {
            if (unref(props).data) {
              _push2(`<div class="flex gap-2"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$2, {
                images: unref(props).data.image
              }, null, _parent2, _scopeId));
              _push2(`<div class="flex flex-col gap-2"${_scopeId}><ul${_scopeId}><li${_scopeId}>${ssrInterpolate(unref(props).data.name)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.description)}</li><li${_scopeId}>${ssrInterpolate((_a = unref(props).data.category) == null ? void 0 : _a.name)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.price_excluding_tax)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.price_including_tax)}</li></ul><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.quantity)} ${ssrInterpolate(unref(form).errors.user_id)} ${ssrInterpolate(unref(form).errors.product_id)}</p></div><div style="${ssrRenderStyle(unref(props).errors ? null : { display: "none" })}"${_scopeId}>${ssrInterpolate(unref(props).errors.quantity)} ${ssrInterpolate(unref(props).errors.user_id)} ${ssrInterpolate(unref(props).errors.product_id)}</div><div class="flex gap-2"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.product.edit", { id: unref(props).data.id }),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`編集`);
                  } else {
                    return [
                      createTextVNode("編集")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.stock.show", { id: unref(props).data.id }),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`在庫`);
                  } else {
                    return [
                      createTextVNode("在庫")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.product.destroy", { id: unref(props).data.id }),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`削除`);
                  } else {
                    return [
                      createTextVNode("削除")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div></div></div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              unref(props).data ? (openBlock(), createBlock("div", {
                key: 0,
                class: "flex gap-2"
              }, [
                createVNode(_sfc_main$2, {
                  images: unref(props).data.image
                }, null, 8, ["images"]),
                createVNode("div", { class: "flex flex-col gap-2" }, [
                  createVNode("ul", null, [
                    createVNode("li", null, toDisplayString(unref(props).data.name), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.description), 1),
                    createVNode("li", null, toDisplayString((_b = unref(props).data.category) == null ? void 0 : _b.name), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.price_excluding_tax), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.price_including_tax), 1)
                  ]),
                  withDirectives(createVNode("div", null, [
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.quantity) + " " + toDisplayString(unref(form).errors.user_id) + " " + toDisplayString(unref(form).errors.product_id), 1)
                  ], 512), [
                    [vShow, unref(form).errors]
                  ]),
                  withDirectives(createVNode("div", null, toDisplayString(unref(props).errors.quantity) + " " + toDisplayString(unref(props).errors.user_id) + " " + toDisplayString(unref(props).errors.product_id), 513), [
                    [vShow, unref(props).errors]
                  ]),
                  createVNode("div", { class: "flex gap-2" }, [
                    createVNode(unref(Link), {
                      href: _ctx.route("admin.product.edit", { id: unref(props).data.id }),
                      class: "btn"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("編集")
                      ]),
                      _: 1
                    }, 8, ["href"]),
                    createVNode(unref(Link), {
                      href: _ctx.route("admin.stock.show", { id: unref(props).data.id }),
                      class: "btn"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("在庫")
                      ]),
                      _: 1
                    }, 8, ["href"]),
                    createVNode(unref(Link), {
                      href: _ctx.route("admin.product.destroy", { id: unref(props).data.id }),
                      class: "btn"
                    }, {
                      default: withCtx(() => [
                        createTextVNode("削除")
                      ]),
                      _: 1
                    }, 8, ["href"])
                  ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/ProductDetail.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
