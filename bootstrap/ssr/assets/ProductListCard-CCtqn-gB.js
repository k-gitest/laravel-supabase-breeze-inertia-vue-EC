import { defineComponent, mergeProps, unref, withCtx, openBlock, createBlock, createVNode, toDisplayString, createCommentVNode, withModifiers, createTextVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
import { Link, router } from "@inertiajs/vue3";
import { i as isoDateGenerator } from "./isoDateGenerator-zYlad1ZJ.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductListCard",
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
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "card bg-base-100 shadow-xl relative" }, _attrs))}>`);
      if (_ctx.created_at >= unref(isoDateGenerator)()) {
        _push(`<div><span class="absolute top-0 end-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white z-10">new</span></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(Link), {
        href: _ctx.route(_ctx.route_show, { id: _ctx.id }),
        class: "flex flex-col h-full justify-between rounded-lg"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (_ctx.image && _ctx.image.length) {
              _push2(`<figure class="h-56"${_scopeId}><img${ssrRenderAttr("src", unref(supabaseURL) + _ctx.image[0].path)}${_scopeId}></figure>`);
            } else {
              _push2(`<figure class="h-56"${_scopeId}><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))} class="rounded-lg"${_scopeId}></figure>`);
            }
            _push2(`<div class="card-body p-2 justify-end"${_scopeId}><h2 class="card-title"${_scopeId}>${ssrInterpolate(_ctx.name)}</h2><p${_scopeId}>${ssrInterpolate(_ctx.description)}</p><p${_scopeId}>${ssrInterpolate(_ctx.price_excluding_tax)}</p><p${_scopeId}>${ssrInterpolate(_ctx.price_including_tax)}</p><div class="card-actions justify-end"${_scopeId}><div class="badge badge-outline text-xs"${_scopeId}>${ssrInterpolate(_ctx.category_name)}</div></div>`);
            if (_ctx.stock && _ctx.stock > 0 && _ctx.stock < 5) {
              _push2(`<div${_scopeId}> 在庫数：残り僅か </div>`);
            } else if (_ctx.stock === 0 || !_ctx.stock) {
              _push2(`<div${_scopeId}><p class="text-red-400"${_scopeId}>売り切れ</p></div>`);
            } else {
              _push2(`<!---->`);
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
              _ctx.image && _ctx.image.length ? (openBlock(), createBlock("figure", {
                key: 0,
                class: "h-56"
              }, [
                createVNode("img", {
                  src: unref(supabaseURL) + _ctx.image[0].path
                }, null, 8, ["src"])
              ])) : (openBlock(), createBlock("figure", {
                key: 1,
                class: "h-56"
              }, [
                createVNode("img", {
                  src: unref(supabaseURL) + unref(supabaseNoImage),
                  class: "rounded-lg"
                }, null, 8, ["src"])
              ])),
              createVNode("div", { class: "card-body p-2 justify-end" }, [
                createVNode("h2", { class: "card-title" }, toDisplayString(_ctx.name), 1),
                createVNode("p", null, toDisplayString(_ctx.description), 1),
                createVNode("p", null, toDisplayString(_ctx.price_excluding_tax), 1),
                createVNode("p", null, toDisplayString(_ctx.price_including_tax), 1),
                createVNode("div", { class: "card-actions justify-end" }, [
                  createVNode("div", { class: "badge badge-outline text-xs" }, toDisplayString(_ctx.category_name), 1)
                ]),
                _ctx.stock && _ctx.stock > 0 && _ctx.stock < 5 ? (openBlock(), createBlock("div", { key: 0 }, " 在庫数：残り僅か ")) : _ctx.stock === 0 || !_ctx.stock ? (openBlock(), createBlock("div", { key: 1 }, [
                  createVNode("p", { class: "text-red-400" }, "売り切れ")
                ])) : createCommentVNode("", true),
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
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ProductListCard.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
