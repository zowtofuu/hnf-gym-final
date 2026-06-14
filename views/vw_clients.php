<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <style>
        .sort-header {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: inherit;
            font-weight: inherit;
            white-space: nowrap;
        }
        .sort-header:hover {
            opacity: 0.75;
        }
        .sort-header.sort-active {
            color: var(--color-primary, #4f46e5);
        }
        .sort-icon {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/navbar.php'; ?>
    <div class="wrapper">
        <h3 class="legend">Clients</h3>

        <!-- CONTROLS -->
        <section class="flex justify-between pb-md">
            <form method="GET" action="../controllers/ctr_clients.php">
                <input class="capitalize rounded-sm px-md py-sm focus-visible" type="search" name="search"
                    placeholder="Search client..." value="<?= htmlspecialchars($searchTerm ?? '') ?>">

                <input class="capitalize rounded-sm px-md py-sm cursor-pointer btn-primary" type="submit" value="Search">

                <a class="capitalize rounded-sm px-md py-sm btn-anchor btn-secondary"
                    href="../controllers/ctr_clients.php">Reset</a>
            </form>
            <a class="icon-button-text px-md py-sm rounded-sm" href="../controllers/ctr_add-client.php" title="Add New Client">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path
                    d="M451.5-131.5Q440-143 440-160v-280H160q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520h280v-280q0-17 11.5-28.5T480-840q17 0 28.5 11.5T520-800v280h280q17 0 28.5 11.5T840-480q0 17-11.5 28.5T800-440H520v280q0 17-11.5 28.5T480-120q-17 0-28.5-11.5Z" />
                </svg>
                <span>Add Client</span>
            </a>
        </section>

        <!-- TABLE -->
        <section>
            <?php
            $columnsToHide = ['client_id', 'created_at'];

            $columnLabels = [
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'birthday' => 'Birth Date',
                'contact' => 'Contact Number'
            ];

            $visibleColumns = [];

            if (!empty($clients)) {
                $visibleColumns = array_keys($clients[0]);

                $visibleColumns = array_filter($visibleColumns, function ($column) use ($columnsToHide) {
                    return !in_array($column, $columnsToHide, true);
                });
            }

            $sortableColumns = ['first_name', 'last_name', 'birthday'];

            function buildSortUrl(string $column, string $currentSortBy, string $currentSortOrder, string $searchTerm): string {
                $newOrder = ($currentSortBy === $column && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                $params = ['sort_by' => $column, 'sort_order' => $newOrder];
                if ($searchTerm !== '') $params['search'] = $searchTerm;
                return '../controllers/ctr_clients.php?' . http_build_query($params);
            }
            ?>

            <table>
                <thead>
                    <tr>
                        <?php foreach ($visibleColumns as $column): ?>
                            <th>
                                <?php if (in_array($column, $sortableColumns, true)): ?>
                                    <?php
                                        $isActive = ($sortBy ?? '') === $column;
                                        $nextOrder = ($isActive && ($sortOrder ?? 'asc') === 'asc') ? 'desc' : 'asc';
                                        $url = buildSortUrl($column, $sortBy ?? '', $sortOrder ?? 'asc', $searchTerm ?? '');
                                    ?>
                                    <a class="sort-header <?= $isActive ? 'sort-active' : '' ?>" href="<?= htmlspecialchars($url) ?>">
                                        <?= htmlspecialchars($columnLabels[$column] ?? ucwords(str_replace('_', ' ', $column))) ?>
                                        <span class="sort-icon">
                                            <?php if ($isActive && ($sortOrder ?? 'asc') === 'asc'): ?>
                                                <!-- Up arrow (ASC) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="16" height="16" fill="currentColor"><path d="M440-160v-487L216-423l-56-57 320-320 320 320-56 57-224-224v487h-80Z"/></svg>
                                            <?php elseif ($isActive && ($sortOrder ?? 'asc') === 'desc'): ?>
                                                <!-- Down arrow (DESC) -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="16" height="16" fill="currentColor"><path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/></svg>
                                            <?php else: ?>
                                                <!-- Up-down neutral arrow -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="16" height="16" fill="currentColor" style="opacity:0.4"><path d="M320-440v-287L217-624l-57-56 200-200 200 200-57 56-103-103v287h-80ZM600-80 400-280l57-56 103 103v-287h80v287l103-103 57 56L600-80Z"/></svg>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($columnLabels[$column] ?? ucwords(str_replace('_', ' ', $column))) ?>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>

                        <?php if (!empty($clients)): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($clients)): ?>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <?php foreach ($visibleColumns as $column): ?>
                                    <td>
                                        <?= htmlspecialchars($column === 'birthday'? formatReadableDate($client[$column] ?? '') : ($client[$column] ?? '')) ?>
                                    </td>
                                <?php endforeach; ?>

                                <td>
                                    <a class="icon-button-plain" title="Edit Client"
                                        href="../controllers/ctr_update-client.php?id=<?= htmlspecialchars($client['client_id']) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path
                                                d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                        </svg>
                                    </a>

                                    <form class="form-as-button" method="POST" title="Delete Client" action="../controllers/ctr_clients.php">
                                        <input type="hidden" name="client_id"
                                            value="<?= htmlspecialchars($client['client_id']) ?>">
                                        <button type="submit" name="delete_client"
                                            onclick="return confirm('Delete this client?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                                <path
                                                    d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM428.5-291.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5ZM280-720v520-520Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php include __DIR__ . '/../components/alert.php'; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>