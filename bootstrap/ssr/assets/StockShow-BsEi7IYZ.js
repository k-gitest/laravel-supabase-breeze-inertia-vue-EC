import { defineComponent, computed, unref, withCtx, createTextVNode, useSSRContext, ref, createVNode, openBlock, createBlock, toDisplayString, Fragment, renderList, withDirectives, vModelText, createCommentVNode, vShow } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrIncludeBooleanAttr, ssrRenderStyle, ssrRenderComponent } from "vue/server-renderer";
import { useForm, Link, usePage, Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./AdminEcLayout-93pFsWU1.js";
import { _ as _sfc_main$4 } from "./EcImageGallery-BXIFEkZy.js";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./ApplicationLogo-B2173abF.js";
import "./DropdownLink-O6UtYnah.js";
import "./supabase-B-jbb2wE.js";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "WarehouseStockSelectbox",
  __ssrInlineRender: true,
  props: {
    warehouses: {},
    product_id: {},
    stock: {}
  },
  setup(__props) {
    const props = __props;
    const form = useForm({
      warehouse_id: "",
      product_id: props.product_id,
      quantity: "",
      reserved_quantity: ""
    });
    const isFormValid = computed(() => {
      return form.quantity !== null && form.quantity !== "" && form.reserved_quantity !== null && form.reserved_quantity !== "";
    });
    const isWarehouseIdPresent = (id) => {
      if (props.stock && props.stock.length) {
        const result = props.stock.some((stock) => {
          if (stock.warehouse)
            return stock.warehouse.id === id;
        });
        return !result;
      } else {
        return true;
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(_attrs)}>`);
      if (props.warehouses && props.warehouses.length) {
        _push(`<div><form><div class="flex flex-col gap-2"><div class="flex flex-col gap-2"><label for="warehouse">倉庫を選択</label><select id="warehouse" class="select select-bordered select-sm w-full"><option disabled selected value="">倉庫を選択してください</option><!--[-->`);
        ssrRenderList(props.warehouses, (warehouse) => {
          _push(`<!--[-->`);
          if (isWarehouseIdPresent(warehouse.id)) {
            _push(`<option${ssrRenderAttr("value", warehouse.id)}>${ssrInterpolate(warehouse.name)}</option>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]--></select></div>`);
        if (unref(form).warehouse_id) {
          _push(`<div class="flex flex-col gap-2"><label for="stock_quantity">在庫数： <input id="stock_quantity" type="number"${ssrRenderAttr("value", unref(form).quantity)} class="border" min="0" max="100"></label><label for="stock_reserve_quantity">予約在庫数： <input id="stock_reserved_quantity" type="number"${ssrRenderAttr("value", unref(form).reserved_quantity)} class="border" min="0" max="100"></label></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<button type="submit" class="btn"${ssrIncludeBooleanAttr(!isFormValid.value || unref(form).processing) ? " disabled" : ""}>登録</button><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"><p class="text-red-400">${ssrInterpolate(unref(form).errors.quantity)}</p><p class="text-red-400">${ssrInterpolate(unref(form).errors.reserved_quantity)}</p></div></div></form></div>`);
      } else {
        _push(`<div><p>倉庫が登録されていません</p>`);
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("admin.warehouse.create"),
          class: "btn"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`倉庫を登録する`);
            } else {
              return [
                createTextVNode("倉庫を登録する")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/WarehouseStockSelectbox.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = {};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs) {
  _push(`<thead${ssrRenderAttrs(_attrs)}><tr><th></th><th>倉庫名</th><th>倉庫住所</th><th>在庫数</th><th>予約在庫数</th><th></th></tr></thead>`);
}
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/StockTableHead.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const StockTableHead = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["ssrRender", _sfc_ssrRender]]);
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "StockShow",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const quantity = ref({});
    const reservedQuantity = ref({});
    if (props.data.stock) {
      for (const stock of props.data.stock) {
        quantity.value[stock.id] = stock.quantity;
        reservedQuantity.value[stock.id] = stock.reserved_quantity;
      }
    }
    const submit = (id, quantity2, reserved) => {
      router.put("/admin/stock/update", {
        data: {
          id,
          quantity: quantity2,
          reserved_quantity: reserved
        },
        preserveState: false
      });
    };
    const deleteStock = (id) => {
      router.delete("/admin/stock/delete", {
        data: {
          id
        },
        preserveState: false
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Stock Show" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$3, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Stock Show</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Stock Show")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b;
          if (_push2) {
            if (unref(props).data) {
              _push2(`<div class="flex gap-2"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$4, {
                images: unref(props).data.image
              }, null, _parent2, _scopeId));
              _push2(`<div class="flex flex-col gap-2"${_scopeId}><ul${_scopeId}><li${_scopeId}>${ssrInterpolate(unref(props).data.name)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.description)}</li><li${_scopeId}>${ssrInterpolate((_a = unref(props).data.category) == null ? void 0 : _a.name)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.price_excluding_tax)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.price_including_tax)}</li><li class="flex gap-2"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.product.edit", { id: unref(props).data.id }),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`商品編集`);
                  } else {
                    return [
                      createTextVNode("商品編集")
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
                    _push3(`商品削除`);
                  } else {
                    return [
                      createTextVNode("商品削除")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</li></ul>`);
              if (unref(props).data.stock && unref(props).data.stock.length) {
                _push2(`<div${_scopeId}><div class="overflow-x-auto"${_scopeId}><table class="table"${_scopeId}>`);
                _push2(ssrRenderComponent(StockTableHead, null, null, _parent2, _scopeId));
                _push2(`<tbody${_scopeId}><!--[-->`);
                ssrRenderList(unref(props).data.stock, (stock) => {
                  _push2(`<!--[-->`);
                  if (stock.warehouse) {
                    _push2(`<tr${_scopeId}><th${_scopeId}>${ssrInterpolate(stock.warehouse.id)}</th><td${_scopeId}>${ssrInterpolate(stock.warehouse.name)}</td><td${_scopeId}>${ssrInterpolate(stock.warehouse.location)}</td><td${_scopeId}><input type="number"${ssrRenderAttr("value", quantity.value[stock.id])} min="0" max="100"${_scopeId}></td><td${_scopeId}><input type="number"${ssrRenderAttr("value", reservedQuantity.value[stock.id])} min="0" max="100"${_scopeId}></td><td class="flex gap-2"${_scopeId}><button class="btn btn-sm"${_scopeId}>変更</button><button class="btn btn-sm"${_scopeId}>削除</button></td></tr>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`<!--]-->`);
                });
                _push2(`<!--]--></tbody></table></div></div>`);
              } else {
                _push2(`<!---->`);
              }
              if (unref(props).data.stock && unref(props).data.stock.length) {
                _push2(ssrRenderComponent(_sfc_main$2, {
                  warehouses: unref(props).warehouse,
                  product_id: unref(props).data.id,
                  stock: unref(props).data.stock
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<!--[--> 在庫が登録されていません `);
                _push2(ssrRenderComponent(_sfc_main$2, {
                  warehouses: unref(props).warehouse,
                  product_id: unref(props).data.id
                }, null, _parent2, _scopeId));
                _push2(`<!--]-->`);
              }
              _push2(`<div style="${ssrRenderStyle(unref(props).errors ? null : { display: "none" })}"${_scopeId}>${ssrInterpolate(unref(props).errors.quantity)} ${ssrInterpolate(unref(props).errors.user_id)} ${ssrInterpolate(unref(props).errors.product_id)}</div></div></div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              unref(props).data ? (openBlock(), createBlock("div", {
                key: 0,
                class: "flex gap-2"
              }, [
                createVNode(_sfc_main$4, {
                  images: unref(props).data.image
                }, null, 8, ["images"]),
                createVNode("div", { class: "flex flex-col gap-2" }, [
                  createVNode("ul", null, [
                    createVNode("li", null, toDisplayString(unref(props).data.name), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.description), 1),
                    createVNode("li", null, toDisplayString((_b = unref(props).data.category) == null ? void 0 : _b.name), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.price_excluding_tax), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.price_including_tax), 1),
                    createVNode("li", { class: "flex gap-2" }, [
                      createVNode(unref(Link), {
                        href: _ctx.route("admin.product.edit", { id: unref(props).data.id }),
                        class: "btn"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("商品編集")
                        ]),
                        _: 1
                      }, 8, ["href"]),
                      createVNode(unref(Link), {
                        href: _ctx.route("admin.product.destroy", { id: unref(props).data.id }),
                        class: "btn"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("商品削除")
                        ]),
                        _: 1
                      }, 8, ["href"])
                    ])
                  ]),
                  unref(props).data.stock && unref(props).data.stock.length ? (openBlock(), createBlock("div", { key: 0 }, [
                    createVNode("div", { class: "overflow-x-auto" }, [
                      createVNode("table", { class: "table" }, [
                        createVNode(StockTableHead),
                        createVNode("tbody", null, [
                          (openBlock(true), createBlock(Fragment, null, renderList(unref(props).data.stock, (stock) => {
                            return openBlock(), createBlock(Fragment, {
                              key: stock.id
                            }, [
                              stock.warehouse ? (openBlock(), createBlock("tr", { key: 0 }, [
                                createVNode("th", null, toDisplayString(stock.warehouse.id), 1),
                                createVNode("td", null, toDisplayString(stock.warehouse.name), 1),
                                createVNode("td", null, toDisplayString(stock.warehouse.location), 1),
                                createVNode("td", null, [
                                  withDirectives(createVNode("input", {
                                    type: "number",
                                    "onUpdate:modelValue": ($event) => quantity.value[stock.id] = $event,
                                    min: "0",
                                    max: "100"
                                  }, null, 8, ["onUpdate:modelValue"]), [
                                    [vModelText, quantity.value[stock.id]]
                                  ])
                                ]),
                                createVNode("td", null, [
                                  withDirectives(createVNode("input", {
                                    type: "number",
                                    "onUpdate:modelValue": ($event) => reservedQuantity.value[stock.id] = $event,
                                    min: "0",
                                    max: "100"
                                  }, null, 8, ["onUpdate:modelValue"]), [
                                    [vModelText, reservedQuantity.value[stock.id]]
                                  ])
                                ]),
                                createVNode("td", { class: "flex gap-2" }, [
                                  createVNode("button", {
                                    onClick: ($event) => submit(stock.id, quantity.value[stock.id], reservedQuantity.value[stock.id]),
                                    class: "btn btn-sm"
                                  }, "変更", 8, ["onClick"]),
                                  createVNode("button", {
                                    onClick: ($event) => deleteStock(stock.id),
                                    class: "btn btn-sm"
                                  }, "削除", 8, ["onClick"])
                                ])
                              ])) : createCommentVNode("", true)
                            ], 64);
                          }), 128))
                        ])
                      ])
                    ])
                  ])) : createCommentVNode("", true),
                  unref(props).data.stock && unref(props).data.stock.length ? (openBlock(), createBlock(_sfc_main$2, {
                    key: 1,
                    warehouses: unref(props).warehouse,
                    product_id: unref(props).data.id,
                    stock: unref(props).data.stock
                  }, null, 8, ["warehouses", "product_id", "stock"])) : (openBlock(), createBlock(Fragment, { key: 2 }, [
                    createTextVNode(" 在庫が登録されていません "),
                    createVNode(_sfc_main$2, {
                      warehouses: unref(props).warehouse,
                      product_id: unref(props).data.id
                    }, null, 8, ["warehouses", "product_id"])
                  ], 64)),
                  withDirectives(createVNode("div", null, toDisplayString(unref(props).errors.quantity) + " " + toDisplayString(unref(props).errors.user_id) + " " + toDisplayString(unref(props).errors.product_id), 513), [
                    [vShow, unref(props).errors]
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/StockShow.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
