import { defineComponent, unref, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderList } from "vue/server-renderer";
import { usePage, Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import { _ as _sfc_main$2 } from "./EcLayout-Cwc_Co1H.js";
import { _ as _sfc_main$4 } from "./ProductListCard-CCtqn-gB.js";
import { _ as _sfc_main$3, a as _sfc_main$5 } from "./SearchBox-ChRR4fMR.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
import "./isoDateGenerator-zYlad1ZJ.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductAllList",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const addFavorite = (id) => {
      router.visit(`/favorite`, {
        method: "post",
        data: {
          product_id: id
        },
        preserveState: false,
        preserveScroll: true,
        onSuccess: (res) => {
          router.reload();
        }
      });
    };
    const searchSubmit = (formdata) => {
      router.get(
        route("search"),
        { q: formdata.q, category_ids: formdata.category_ids },
        {
          preserveState: false,
          preserveScroll: true,
          onSuccess: () => {
            console.log("success");
          }
        }
      );
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "ProductAllList" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>ProductAllList</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "ProductAllList")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, null, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex gap-5 w-full"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    onSearchSubmit: searchSubmit,
                    categories: unref(props).category,
                    filters: unref(props).filters
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="grow"${_scopeId2}>`);
                  if (unref(props).pagedata.data.length) {
                    _push3(`<div${_scopeId2}><div class="text-center mb-5"${_scopeId2}><p class="font-semibold text-xl text-gray-800"${_scopeId2}>商品一覧</p></div><div class="grid grid-cols-4 gap-5 justify-items-center"${_scopeId2}><!--[-->`);
                    ssrRenderList(unref(props).pagedata.data, (product) => {
                      var _a, _b, _c;
                      _push3(ssrRenderComponent(_sfc_main$4, {
                        image: product.image,
                        name: product.name,
                        id: product.id,
                        description: product.description,
                        price_excluding_tax: product.price_excluding_tax.toString(),
                        price_including_tax: product.price_including_tax.toString(),
                        category_name: (_a = product.category) == null ? void 0 : _a.name,
                        created_at: product.created_at,
                        route_show: `product.show`,
                        mode: ((_b = product.favorite) == null ? void 0 : _b.some((item) => {
                          var _a2;
                          return item.user_id === ((_a2 = unref(props).auth.user) == null ? void 0 : _a2.id);
                        })) ? `favorite.disable` : `favorite.enable`,
                        onAddFavorite: addFavorite,
                        count: (_c = product.favorite) == null ? void 0 : _c.length,
                        stock: product == null ? void 0 : product.stock_sum_quantity
                      }, null, _parent3, _scopeId2));
                    });
                    _push3(`<!--]--></div><div${_scopeId2}>`);
                    _push3(ssrRenderComponent(_sfc_main$5, {
                      links: unref(props).pagedata.links
                    }, null, _parent3, _scopeId2));
                    _push3(`</div></div>`);
                  } else {
                    _push3(`<div${_scopeId2}><p class="text-center"${_scopeId2}>該当商品がありません</p></div>`);
                  }
                  _push3(`</div></div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex gap-5 w-full" }, [
                      createVNode(_sfc_main$3, {
                        onSearchSubmit: searchSubmit,
                        categories: unref(props).category,
                        filters: unref(props).filters
                      }, null, 8, ["categories", "filters"]),
                      createVNode("div", { class: "grow" }, [
                        unref(props).pagedata.data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                          createVNode("div", { class: "text-center mb-5" }, [
                            createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "商品一覧")
                          ]),
                          createVNode("div", { class: "grid grid-cols-4 gap-5 justify-items-center" }, [
                            (openBlock(true), createBlock(Fragment, null, renderList(unref(props).pagedata.data, (product) => {
                              var _a, _b, _c;
                              return openBlock(), createBlock(_sfc_main$4, {
                                key: product.id,
                                image: product.image,
                                name: product.name,
                                id: product.id,
                                description: product.description,
                                price_excluding_tax: product.price_excluding_tax.toString(),
                                price_including_tax: product.price_including_tax.toString(),
                                category_name: (_a = product.category) == null ? void 0 : _a.name,
                                created_at: product.created_at,
                                route_show: `product.show`,
                                mode: ((_b = product.favorite) == null ? void 0 : _b.some((item) => {
                                  var _a2;
                                  return item.user_id === ((_a2 = unref(props).auth.user) == null ? void 0 : _a2.id);
                                })) ? `favorite.disable` : `favorite.enable`,
                                onAddFavorite: addFavorite,
                                count: (_c = product.favorite) == null ? void 0 : _c.length,
                                stock: product == null ? void 0 : product.stock_sum_quantity
                              }, null, 8, ["image", "name", "id", "description", "price_excluding_tax", "price_including_tax", "category_name", "created_at", "route_show", "mode", "count", "stock"]);
                            }), 128))
                          ]),
                          createVNode("div", null, [
                            createVNode(_sfc_main$5, {
                              links: unref(props).pagedata.links
                            }, null, 8, ["links"])
                          ])
                        ])) : (openBlock(), createBlock("div", { key: 1 }, [
                          createVNode("p", { class: "text-center" }, "該当商品がありません")
                        ]))
                      ])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, null, {
                default: withCtx(() => [
                  createVNode("div", { class: "flex gap-5 w-full" }, [
                    createVNode(_sfc_main$3, {
                      onSearchSubmit: searchSubmit,
                      categories: unref(props).category,
                      filters: unref(props).filters
                    }, null, 8, ["categories", "filters"]),
                    createVNode("div", { class: "grow" }, [
                      unref(props).pagedata.data.length ? (openBlock(), createBlock("div", { key: 0 }, [
                        createVNode("div", { class: "text-center mb-5" }, [
                          createVNode("p", { class: "font-semibold text-xl text-gray-800" }, "商品一覧")
                        ]),
                        createVNode("div", { class: "grid grid-cols-4 gap-5 justify-items-center" }, [
                          (openBlock(true), createBlock(Fragment, null, renderList(unref(props).pagedata.data, (product) => {
                            var _a, _b, _c;
                            return openBlock(), createBlock(_sfc_main$4, {
                              key: product.id,
                              image: product.image,
                              name: product.name,
                              id: product.id,
                              description: product.description,
                              price_excluding_tax: product.price_excluding_tax.toString(),
                              price_including_tax: product.price_including_tax.toString(),
                              category_name: (_a = product.category) == null ? void 0 : _a.name,
                              created_at: product.created_at,
                              route_show: `product.show`,
                              mode: ((_b = product.favorite) == null ? void 0 : _b.some((item) => {
                                var _a2;
                                return item.user_id === ((_a2 = unref(props).auth.user) == null ? void 0 : _a2.id);
                              })) ? `favorite.disable` : `favorite.enable`,
                              onAddFavorite: addFavorite,
                              count: (_c = product.favorite) == null ? void 0 : _c.length,
                              stock: product == null ? void 0 : product.stock_sum_quantity
                            }, null, 8, ["image", "name", "id", "description", "price_excluding_tax", "price_including_tax", "category_name", "created_at", "route_show", "mode", "count", "stock"]);
                          }), 128))
                        ]),
                        createVNode("div", null, [
                          createVNode(_sfc_main$5, {
                            links: unref(props).pagedata.links
                          }, null, 8, ["links"])
                        ])
                      ])) : (openBlock(), createBlock("div", { key: 1 }, [
                        createVNode("p", { class: "text-center" }, "該当商品がありません")
                      ]))
                    ])
                  ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/ProductAllList.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
