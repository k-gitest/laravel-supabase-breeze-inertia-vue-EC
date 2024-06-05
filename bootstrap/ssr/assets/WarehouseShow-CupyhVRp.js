import { defineComponent, mergeProps, unref, withCtx, openBlock, createBlock, createVNode, toDisplayString, withModifiers, createTextVNode, createCommentVNode, useSSRContext, Fragment, renderList } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { Link, router, usePage, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./AdminEcLayout-93pFsWU1.js";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
import { i as isoDateGenerator } from "./isoDateGenerator-zYlad1ZJ.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "AdminProductListCard",
  __ssrInlineRender: true,
  props: {
    image: {},
    name: {},
    id: {},
    description: {},
    price_excluding_tax: {},
    price_including_tax: {},
    category_name: {},
    created_at: {},
    route_show: {},
    route_edit: {},
    route_destroy: {},
    mode: {},
    count: {},
    stock: {}
  },
  emits: ["addFavorite"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    const handleFavorite = (id) => {
      emit("addFavorite", id);
    };
    const deleteFavorite = (id) => {
      router.delete(`/favorite/${id}`, {
        preserveState: false
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "card w-96 bg-base-100 shadow-xl max-w-48 relative" }, _attrs))}>`);
      if (_ctx.created_at >= unref(isoDateGenerator)()) {
        _push(`<div><span class="absolute top-0 end-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white z-10">new</span></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(Link), {
        href: _ctx.route(_ctx.route_show, { id: _ctx.id })
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (_ctx.image && _ctx.image.length) {
              _push2(`<figure${_scopeId}><img${ssrRenderAttr("src", unref(supabaseURL) + _ctx.image[0].path)} class="rounded-lg"${_scopeId}></figure>`);
            } else {
              _push2(`<figure${_scopeId}><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))} class="rounded-lg"${_scopeId}></figure>`);
            }
            _push2(`<div class="card-body p-2"${_scopeId}><h2 class="card-title"${_scopeId}>${ssrInterpolate(_ctx.name)}</h2><p${_scopeId}>${ssrInterpolate(_ctx.description)}</p><p${_scopeId}>${ssrInterpolate(_ctx.price_excluding_tax)}</p><p${_scopeId}>${ssrInterpolate(_ctx.price_including_tax)}</p><div class="card-actions justify-end"${_scopeId}><div class="badge badge-outline text-xs"${_scopeId}>${ssrInterpolate(_ctx.category_name)}</div></div>`);
            if (_ctx.stock) {
              _push2(`<div${_scopeId}> 在庫数：${ssrInterpolate(_ctx.stock)}</div>`);
            } else {
              _push2(`<div${_scopeId}><p class="text-red-400"${_scopeId}>在庫を登録してください</p></div>`);
            }
            if (_ctx.mode === "favorite.enable") {
              _push2(`<button class="btn btn-sm"${_scopeId}>お気に入りに追加<span class="badge"${_scopeId}>${ssrInterpolate(_ctx.count)}</span></button>`);
            } else {
              _push2(`<!---->`);
            }
            if (_ctx.mode === "favorite.disable") {
              _push2(`<button class="btn btn-sm" disabled${_scopeId}>お気に入りに追加<span class="badge"${_scopeId}>${ssrInterpolate(_ctx.count)}</span></button>`);
            } else {
              _push2(`<!---->`);
            }
            if (_ctx.mode === "favorite.delete") {
              _push2(`<button class="btn btn-sm"${_scopeId}>削除</button>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              _ctx.image && _ctx.image.length ? (openBlock(), createBlock("figure", { key: 0 }, [
                createVNode("img", {
                  src: unref(supabaseURL) + _ctx.image[0].path,
                  class: "rounded-lg"
                }, null, 8, ["src"])
              ])) : (openBlock(), createBlock("figure", { key: 1 }, [
                createVNode("img", {
                  src: unref(supabaseURL) + unref(supabaseNoImage),
                  class: "rounded-lg"
                }, null, 8, ["src"])
              ])),
              createVNode("div", { class: "card-body p-2" }, [
                createVNode("h2", { class: "card-title" }, toDisplayString(_ctx.name), 1),
                createVNode("p", null, toDisplayString(_ctx.description), 1),
                createVNode("p", null, toDisplayString(_ctx.price_excluding_tax), 1),
                createVNode("p", null, toDisplayString(_ctx.price_including_tax), 1),
                createVNode("div", { class: "card-actions justify-end" }, [
                  createVNode("div", { class: "badge badge-outline text-xs" }, toDisplayString(_ctx.category_name), 1)
                ]),
                _ctx.stock ? (openBlock(), createBlock("div", { key: 0 }, " 在庫数：" + toDisplayString(_ctx.stock), 1)) : (openBlock(), createBlock("div", { key: 1 }, [
                  createVNode("p", { class: "text-red-400" }, "在庫を登録してください")
                ])),
                _ctx.mode === "favorite.enable" ? (openBlock(), createBlock("button", {
                  key: 2,
                  onClick: withModifiers(($event) => handleFavorite(_ctx.id), ["stop", "prevent"]),
                  class: "btn btn-sm"
                }, [
                  createTextVNode("お気に入りに追加"),
                  createVNode("span", { class: "badge" }, toDisplayString(_ctx.count), 1)
                ], 8, ["onClick"])) : createCommentVNode("", true),
                _ctx.mode === "favorite.disable" ? (openBlock(), createBlock("button", {
                  key: 3,
                  class: "btn btn-sm",
                  disabled: ""
                }, [
                  createTextVNode("お気に入りに追加"),
                  createVNode("span", { class: "badge" }, toDisplayString(_ctx.count), 1)
                ])) : createCommentVNode("", true),
                _ctx.mode === "favorite.delete" ? (openBlock(), createBlock("button", {
                  key: 4,
                  onClick: withModifiers(($event) => deleteFavorite(_ctx.id), ["stop", "prevent"]),
                  class: "btn btn-sm"
                }, "削除", 8, ["onClick"])) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/AdminProductListCard.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "WarehouseShow",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Warehouse Show" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$2, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>${ssrInterpolate(unref(props).data.name)}</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, toDisplayString(unref(props).data.name), 1)
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (unref(props).data) {
              _push2(`<div${_scopeId}><div class="text-center mb-5"${_scopeId}><p class="font-semibold text-xl text-gray-800"${_scopeId}>${ssrInterpolate(unref(props).data.name)}</p><p${_scopeId}>${ssrInterpolate(unref(props).data.location)}</p></div>`);
              if (unref(props).data.stock && unref(props).data.stock.length) {
                _push2(`<div class="grid grid-cols-4 gap-5 justify-center columns-3"${_scopeId}><!--[-->`);
                ssrRenderList(unref(props).data.stock, (stock) => {
                  var _a;
                  _push2(`<!--[-->`);
                  if (stock.product) {
                    _push2(ssrRenderComponent(_sfc_main$1, {
                      image: stock.product.image,
                      name: stock.product.name,
                      id: stock.product.id,
                      description: stock.product.description,
                      price_excluding_tax: stock.product.price_excluding_tax.toString(),
                      price_including_tax: stock.product.price_including_tax.toString(),
                      category_name: (_a = stock.product.category) == null ? void 0 : _a.name,
                      created_at: stock.product.created_at,
                      route_show: "admin.stock.show",
                      stock: stock.quantity
                    }, null, _parent2, _scopeId));
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`<!--]-->`);
                });
                _push2(`<!--]--></div>`);
              } else {
                _push2(`<div${_scopeId}> 登録商品がありません </div>`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              unref(props).data ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("div", { class: "text-center mb-5" }, [
                  createVNode("p", { class: "font-semibold text-xl text-gray-800" }, toDisplayString(unref(props).data.name), 1),
                  createVNode("p", null, toDisplayString(unref(props).data.location), 1)
                ]),
                unref(props).data.stock && unref(props).data.stock.length ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "grid grid-cols-4 gap-5 justify-center columns-3"
                }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data.stock, (stock) => {
                    var _a;
                    return openBlock(), createBlock(Fragment, {
                      key: stock.id
                    }, [
                      stock.product ? (openBlock(), createBlock(_sfc_main$1, {
                        key: 0,
                        image: stock.product.image,
                        name: stock.product.name,
                        id: stock.product.id,
                        description: stock.product.description,
                        price_excluding_tax: stock.product.price_excluding_tax.toString(),
                        price_including_tax: stock.product.price_including_tax.toString(),
                        category_name: (_a = stock.product.category) == null ? void 0 : _a.name,
                        created_at: stock.product.created_at,
                        route_show: "admin.stock.show",
                        stock: stock.quantity
                      }, null, 8, ["image", "name", "id", "description", "price_excluding_tax", "price_including_tax", "category_name", "created_at", "route_show", "stock"])) : createCommentVNode("", true)
                    ], 64);
                  }), 128))
                ])) : (openBlock(), createBlock("div", { key: 1 }, " 登録商品がありません "))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/WarehouseShow.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
