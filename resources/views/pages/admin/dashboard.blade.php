@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <section class="dashboard-grid" aria-label="Inventory dashboard">
        <article class="panel list-panel">
            <div class="panel-heading">
                <h2>Item List</h2>
                <button class="view-all" data-view="items">View All</button>
            </div>
            <div class="data-table-wrap">
                <table class="data-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item Name <span class="sort">↕</span></th>
                            <th>Image</th>
                            <th>Store</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gas Kitting</td>
                            <td>
                                <div class="product-thumb orange"></div>
                            </td>
                            <td>22 House Store</td>
                            <td>1 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb blue"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>3 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb green"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>5 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb purple"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>5 pcs</td>
                        </tr>
                    </tbody>
                </table>
                <div class="empty-state" id="itemsEmpty">No items match your search.</div>
            </div>
        </article>

        <article class="panel list-panel">
            <div class="panel-heading">
                <h2>Asset List</h2>
                <button class="view-all" data-view="assets">View All</button>
            </div>
            <div class="data-table-wrap">
                <table class="data-table" id="assetsTable">
                    <thead>
                        <tr>
                            <th>Asset Name <span class="sort">↕</span></th>
                            <th>Image</th>
                            <th>Store</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gas Kitting</td>
                            <td>
                                <div class="product-thumb orange"></div>
                            </td>
                            <td>22 House Store</td>
                            <td>1 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb blue"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>3 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb green"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>5 pcs</td>
                        </tr>
                        <tr>
                            <td>Condet</td>
                            <td>
                                <div class="product-thumb purple"></div>
                            </td>
                            <td>HQ Main Store</td>
                            <td>5 pcs</td>
                        </tr>
                    </tbody>
                </table>
                <div class="empty-state" id="assetsEmpty">No assets match your search.</div>
            </div>
        </article>

        <article class="summary-card">
            <h2>Item Summary</h2>
            <div class="summary-split">
                <div class="metric">
                    <div class="metric-icon peach">▤</div>
                    <strong>868</strong><span>Quantity in Hand</span>
                </div>
                <div class="metric">
                    <div class="metric-icon lavender">⌁</div>
                    <strong>200</strong><span>To be received</span>
                </div>
            </div>
        </article>

        <article class="summary-card">
            <h2>Product Summary</h2>
            <div class="summary-split">
                <div class="metric">
                    <div class="metric-icon cyan">♙</div>
                    <strong>31</strong><span>Number of Suppliers</span>
                </div>
                <div class="metric">
                    <div class="metric-icon periwinkle">⌘</div>
                    <strong>21</strong><span>Number of Categories</span>
                </div>
            </div>
        </article>

        <article class="summary-card wide-summary">
            <h2>Total items</h2>
            <div class="summary-split">
                <div class="metric">
                    <div class="metric-icon cyan">♙</div>
                    <strong>31</strong><span>Total Number of Items</span>
                </div>
                <div class="metric">
                    <div class="metric-icon periwinkle">⌘</div>
                    <strong>21</strong><span>To be received</span>
                </div>
            </div>
        </article>

        <article class="summary-card wide-summary">
            <h2>Total assets</h2>
            <div class="summary-split">
                <div class="metric">
                    <div class="metric-icon cyan">♙</div>
                    <strong>31</strong><span>Total Number of assets</span>
                </div>
                <div class="metric">
                    <div class="metric-icon periwinkle">⌘</div>
                    <strong>21</strong><span>To be received</span>
                </div>
            </div>
        </article>
    </section>
@endsection
