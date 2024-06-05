import { defineComponent, unref, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import { usePage, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { _ as _sfc_main$3 } from "./ProductListCard-CCtqn-gB.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
import "./isoDateGenerator-zYlad1ZJ.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "FavoriteIndex",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "FavoriteIndex" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Favorite Index</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Favorite Index")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (unref(props).data.length) {
                    _push3(`<div${_scopeId2}><div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>お気に入り一覧</p></div><div class="grid grid-cols-4 gap-5"${_scopeId2}><!--[-->`);
                    ssrRenderList(unref(props).data, (favorite) => {
                      var _a;
                      _push3(ssrRenderComponent(_sfc_main$3, {
                        image: favorite.product.image,
                        name: favorite.product.name,
                        id: favorite.id,
                        description: favorite.product.description,
                        price_excluding_tax: favorite.product.price_excluding_tax.toString(),
                        price_including_tax: favorite.product.price_including_tax.toString(),
                        category_name: (_a = favorite.product.category) == null ? void 0 : _a.name,
                        created_at: favorite.product.created_at,
                        route_show: `product.show`,
                        mode: `favorite.delete`
                      }, null, _parent3, _scopeId2));
                    });
                    _push3(`<!--]--></div></div>`);
                  } else {
                    _push3(`<div${_scopeId2}> お気に入りはありません </div>`);
                  }
                } else {
                  return [
                    unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                      createVNode("div", { class: "text-center mb-5" }, [
                        createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "お気に入り一覧")
                      ]),
                      createVNode("div", { class: "grid grid-cols-4 gap-5" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (favorite) => {
                          var _a;
                          return openBlock(), createBlock(_sfc_main$3, {
                            key: favorite.id,
                            image: favorite.product.image,
                            name: favorite.product.name,
                            id: favorite.id,
                            description: favorite.product.description,
                            price_excluding_tax: favorite.product.price_excluding_tax.toString(),
                            price_including_tax: favorite.product.price_including_tax.toString(),
                            category_name: (_a = favorite.product.category) == null ? void 0 : _a.name,
                            created_at: favorite.product.created_at,
                            route_show: `product.show`,
                            mode: `favorite.delete`
                          }, null, 8, ["image", "name", "id", "description", "price_excluding_tax", "price_including_tax", "category_name", "created_at", "route_show", "mode"]);
                        }), 128))
                      ])
                    ])) : (openBlock(), createBlock("div", { key: 1 }, " お気に入りはありません "))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  unref(props).data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                    createVNode("div", { class: "text-center mb-5" }, [
                      createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "お気に入り一覧")
                    ]),
                    createVNode("div", { class: "grid grid-cols-4 gap-5" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data, (favorite) => {
                        var _a;
                        return openBlock(), createBlock(_sfc_main$3, {
                          key: favorite.id,
                          image: favorite.product.image,
                          name: favorite.product.name,
                          id: favorite.id,
                          description: favorite.product.description,
                          price_excluding_tax: favorite.product.price_excluding_tax.toString(),
                          price_including_tax: favorite.product.price_including_tax.toString(),
                          category_name: (_a = favorite.product.category) == null ? void 0 : _a.name,
                          created_at: favorite.product.created_at,
                          route_show: `product.show`,
                          mode: `favorite.delete`
                        }, null, 8, ["image", "name", "id", "description", "price_excluding_tax", "price_including_tax", "category_name", "created_at", "route_show", "mode"]);
                      }), 128))
                    ])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, " お気に入りはありません "))
                ]),
                _: 2
              }, 1024)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/FavoriteIndex.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
