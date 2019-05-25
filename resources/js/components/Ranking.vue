<template>
	<div class="container">
		<div class="ranking">
			<header>
				<h2>{{ titulo }}</h2>
			</header>
			
			<section>
				<div class="search">
					<div class="input-group mb-3">
					  <input type="text" class="form-control" placeholder="Buscar por nome">
					  <div class="input-group-append">
					    <button type="submit" class="btn btn-secondary">Buscar</button>
					  </div>
					</div>
				</div>

				<div class="list">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th class="text-right">
									<img src="/svg/poison.svg" class="poison">
									Kills
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(kill, idx) in kills" :key="idx">
								<td>{{ kill.name }}</td>
								<td class="text-center" :style="{ color: getColorValue(kill.kills) }">{{ kill.kills }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'ranking',
		data() {
			return {
				titulo: 'Ranking',
				kills: [
					{ name: 'Udaectus Verum Quiel', kills: 70 },
					{ name: 'Udaectus', kills: 23 },
					{ name: 'Verum', kills: 12 },
					{ name: 'Quiel', kills: -1 },
				],
			}
		},
		methods: {
			getColorValue(value) {
				return value > 0 ? '#fff' : '#f7a600';
			}
		}
	}
</script>

<style lang="sass">
	body
		font-family: 'Roboto', sans-serif
		background: #dadada

	.ranking
		margin: 30px auto 0 auto
		width: 400px
		h2
			text-transform: uppercase
			font-weight: 700

	.search
		input
			background: #dadada
			border-color: #fff
		input:focus
			background: #f3f2f2
		button
			border-radius: 0
			color: #f7a600
			width: 150px

	.list 
		table
			thead
				background: #f7a600
				padding: 0 !important
				tr
					th
						text-transform: uppercase
						font-size: 22px
						font-weight: 700
			tbody
				color: #fff
				tr:nth-child(even)
					background: #646363
				tr:nth-child(odd)
					background: #7c7b7b
				tr:hover
					background: rgba(124,123,123,.4)

	.poison
		width: 27px
</style>