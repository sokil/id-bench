#!/bin/bash

export TERM=png

CURRENT_DIR=$(dirname $(readlink -f $0))
cd "${CURRENT_DIR}/../results"

function draw() {
    BENCH_GROUP=$1
    Y_LABEL=$2
    COLUMN=$3
    OUTPUT_FILE=$4
    SMOOTH=$5

    TITLE="$2 Benchmark"
    X_LABEL="Inserted Rows"
    COLORS=( '#1f77b4' '#ff7f0e' '#2ca02c' '#d62728' '#9467bd' '#8c564b' '#e377c2' '#7f7f7f' '#bcbd22' )
    GNUPLOT_COMMANDS_FILE="gnuplot.commands"

    cat <<- EOF > ${GNUPLOT_COMMANDS_FILE}
set title "$TITLE"
set xlabel "$X_LABEL"
set ylabel "$Y_LABEL"
set key top left
set grid
set term pngcairo size 800,600
set output "$OUTPUT_FILE"
EOF

    PLOT_COMMAND='plot'
    i=0
    for file in ${BENCH_GROUP}*.csv; do
        PLOT_COMMAND="${PLOT_COMMAND} '$file' using 1:${COLUMN} ${SMOOTH} with lines lt 1 lw 2 lc rgb '${COLORS[$i]}' title '${file::-4}',"
        ((i++))
    done

    echo "${PLOT_COMMAND::-1}" >> ${GNUPLOT_COMMANDS_FILE}

    gnuplot ${GNUPLOT_COMMANDS_FILE}

    rm ${GNUPLOT_COMMANDS_FILE}
}

draw "mysql-insert" "Time" 2 "mysql-insert-time.png" "smooth csplines"
draw "mysql-insert" "Index Size" 3 "mysql-insert-indexsize.png" ""
