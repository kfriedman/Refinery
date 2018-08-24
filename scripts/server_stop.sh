#!/bin/bash
#kill $(ps aux | grep '[p]hp queue-worker' | awk '{print $2}')
pkill -x php
