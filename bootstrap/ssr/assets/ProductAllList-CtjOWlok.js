import { defineComponent, ref, computed, mergeProps, unref, withCtx, createTextVNode, useSSRContext, createVNode, openBlock, createBlock } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrIncludeBooleanAttr, ssrRenderList, ssrLooseContain, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { Link, usePage, Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./AdminEcLayout-93pFsWU1.js";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
import { i as isoDateGenerator } from "./isoDateGenerator-zYlad1ZJ.js";
import { _ as _sfc_main$3, a as _sfc_main$4 } from "./SearchBox-ChRR4fMR.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "AdminStockTableList",
  __ssrInlineRender: true,
  props: {
    data: {}
  },
  setup(__props) {
    const props = __props;
    const selectedItems = ref([]);
    const allSelected = computed({
      get: () => props.data.length > 0 && selectedItems.value.length === props.data.length,
      set: (value) => {
        if (value) {
          selectedItems.value = props.data.map((item) => item.id);
        } else {
          selectedItems.value = [];
        }
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "overflow-x-auto" }, _attrs))}><div class="flex justify-end">`);
      _push(ssrRenderComponent(unref(Link), {
        href: _ctx.route("admin.product.create"),
        class: "btn btn-sm"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`新規作成`);
          } else {
            return [
              createTextVNode("新規作成")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><table class="table"><thead><tr><th><label><input type="checkbox"${ssrIncludeBooleanAttr(allSelected.value) ? " checked" : ""} class="checkbox checkbox-xs"></label></th><th>ID</th><th>商品名</th><th>説明</th><th>価格</th><th>税率</th><th>在庫</th><th>編集</th></tr></thead><tbody><!--[-->`);
      ssrRenderList(props.data, (product) => {
        var _a;
        _push(`<tr><th><label><input type="checkbox"${ssrIncludeBooleanAttr(Array.isArray(selectedItems.value) ? ssrLooseContain(selectedItems.value, product.id) : selectedItems.value) ? " checked" : ""}${ssrRenderAttr("value", product.id)} class="checkbox checkbox-xs"></label></th><td>${ssrInterpolate(product.id)}</td><td><div class="flex items-center gap-3"><div class="avatar relative"><div class="mask mask-squircle w-12 h-12">`);
        if (product.image && product.image.length) {
          _push(`<div><img${ssrRenderAttr("src", unref(supabaseURL) + product.image[0].path)}></div>`);
        } else {
          _push(`<div><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))}></div>`);
        }
        _push(`</div>`);
        if (product.created_at >= unref(isoDateGenerator)()) {
          _push(`<div><span class="absolute top-0 start-0 inline-flex items-center py-0.4 px-1.5 rounded-full text-xs font-medium transform -translate-x-1/2 -translate-y-1/2 bg-red-500 text-white z-10">new</span></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div><div><div class="font-bold">${ssrInterpolate(product.name)}</div><div class="text-sm opacity-50">${ssrInterpolate((_a = product.category) == null ? void 0 : _a.name)}</div></div></div></td><td>${ssrInterpolate(product.description)}</td><td>${ssrInterpolate(product.price_excluding_tax)}<br><span class="badge badge-ghost badge-sm">${ssrInterpolate(product.price_including_tax)}</span></td><th>${ssrInterpolate(product.tax_rate)}</th><th>`);
        if (product.stock_sum_quantity) {
          _push(`<!--[-->${ssrInterpolate(product.stock_sum_quantity)}<!--]-->`);
        } else {
          _push(`<!--[--> 未登録 <!--]-->`);
        }
        _push(`</th><th>`);
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("admin.product.edit", { id: product.id }),
          class: "btn btn-ghost btn-xs"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`商品`);
            } else {
              return [
                createTextVNode("商品")
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("admin.stock.show", { id: product.id }),
          class: "btn btn-ghost btn-xs"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`在庫`);
            } else {
              return [
                createTextVNode("在庫")
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(ssrRenderComponent(unref(Link), {
          href: _ctx.route("admin.product.destroy", { id: product.id }),
          class: "btn btn-ghost btn-xs"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`削除`);
            } else {
              return [
                createTextVNode("削除")
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(`</th></tr>`);
      });
      _push(`<!--]--></tbody></table>`);
      if (selectedItems.value.length > 0) {
        _push(`<button class="btn btn-sm">チェックした商品を一括削除</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/AdminStockTableList.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductAllList",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const searchSubmit = (formdata) => {
      router.get(
        route("admin.search"),
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
      _push(ssrRenderComponent(_sfc_main$2, null, {
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
            _push2(`<div class="flex gap-5 w-full"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$3, {
              onSearchSubmit: searchSubmit,
              categories: unref(props).category,
              filters: unref(props).filters
            }, null, _parent2, _scopeId));
            _push2(`<div class="grow"${_scopeId}>`);
            if (unref(props).pagedata.data.length) {
              _push2(`<div${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                data: unref(props).pagedata.data
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<div${_scopeId}><p class="text-center"${_scopeId}>該当商品がありません</p></div>`);
            }
            _push2(ssrRenderComponent(_sfc_main$4, {
              links: unref(props).pagedata.links
            }, null, _parent2, _scopeId));
            _push2(`</div></div>`);
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
                    createVNode(_sfc_main$1, {
                      data: unref(props).pagedata.data
                    }, null, 8, ["data"])
                  ])) : (openBlock(), createBlock("div", { key: 1 }, [
                    createVNode("p", { class: "text-center" }, "該当商品がありません")
                  ])),
                  createVNode(_sfc_main$4, {
                    links: unref(props).pagedata.links
                  }, null, 8, ["links"])
                ])
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/ProductAllList.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
